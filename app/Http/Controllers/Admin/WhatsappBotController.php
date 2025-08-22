<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappAutoReply;
use App\Models\WhatsappBulkMessage;
use App\Models\WhatsappChatGPTPrompt;
use App\Models\WhatsappConversation;
use App\Models\WhatsappTemplate;
use App\Models\WhatsappTemplateCampaign;
use App\Models\WhatsappLeadScore;
use App\Models\WhatsappMessageFlow;
use App\Models\WhatsappDefaultResponse;
use App\Models\WhatsappChatGPTContext;
use App\Models\WhatsappMedia;
use App\Events\WhatsappImageReceived;
use App\Services\WhatsAppCloudApiService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\DelayedResponseJob;
use App\Jobs\FollowUpJob;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WhatsappBotController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppCloudApiService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function index()
    {
        try {
            // Import Workflow models
            $totalWorkflows = \App\Models\Workflow::count();
            $activeConversations = \App\Models\WorkflowConversationHistory::where('message_type', 'incoming')
                ->whereDate('created_at', today())->distinct('whatsapp_number')->count();
            $templatesCount = WhatsappTemplate::count();

            // Calculate success rate from completed workflows
            $completedWorkflows = \App\Models\Workflow::where('status', 'completed')->count();
            $successRate = $totalWorkflows > 0 ? round(($completedWorkflows / $totalWorkflows) * 100, 1) : 0;

            // Recent workflows for dashboard
            $recentWorkflows = \App\Models\Workflow::orderBy('created_at', 'desc')->take(5)->get();

            $stats = [
                'total_workflows' => $totalWorkflows,
                'active_conversations' => $activeConversations,
                'success_rate' => $successRate,
                'templates_count' => $templatesCount,
                'queue_size' => 0 // Placeholder for message queue size
            ];

            return view('admin.whatsapp.dashboard', compact('stats', 'recentWorkflows'));
        } catch (\Exception $e) {
            // Fallback data if models/tables don't exist
            $stats = [
                'total_workflows' => 0,
                'active_conversations' => 0,
                'success_rate' => 0,
                'templates_count' => 0,
                'queue_size' => 0
            ];
            $recentWorkflows = collect();
            
            return view('admin.whatsapp.dashboard', compact('stats', 'recentWorkflows'));
        }
    }

    // Bulk Messages
    public function showBulkMessages()
    {
        $templates = WhatsappTemplate::all();
        $bulkMessages = WhatsappBulkMessage::with('template')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get interaction statistics
        $interactionStats = WhatsappLeadScore::selectRaw('
            category,
            COUNT(*) as total,
            AVG(interaction_score) as avg_score,
            AVG(response_rate) as avg_response_rate
        ')
        ->groupBy('category')
        ->get();

        // Get recent interactions for analysis
        $recentInteractions = WhatsappConversation::with('leadScore')
            ->whereHas('leadScore')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->groupBy('phone');

        return view('admin.pages.whatsapp.bulk-messages', compact(
            'templates',
            'bulkMessages',
            'interactionStats',
            'recentInteractions'
        ));
    }

    public function getTargetedRecipients(Request $request)
    {
        $request->validate([
            'category' => 'required|in:valuable,average,not_interested',
            'min_score' => 'nullable|integer',
            'min_response_rate' => 'nullable|integer',
            'last_interaction_days' => 'nullable|integer',
            'exclude_recent_bulk' => 'nullable|boolean'
        ]);

        $query = WhatsappLeadScore::where('category', $request->category);

        if ($request->min_score) {
            $query->where('interaction_score', '>=', $request->min_score);
        }

        if ($request->min_response_rate) {
            $query->where('response_rate', '>=', $request->min_response_rate);
        }

        if ($request->last_interaction_days) {
            $query->where('last_interaction', '>=', 
                now()->subDays($request->last_interaction_days)
            );
        }

        if ($request->exclude_recent_bulk) {
            $recentRecipients = WhatsappBulkMessage::where('created_at', '>=', now()->subDays(7))
                ->pluck('recipients')
                ->flatMap(function($recipients) {
                    return json_decode($recipients, true);
                })
                ->unique();

            $query->whereNotIn('phone', $recentRecipients);
        }

        $recipients = $query->get();

        return response()->json([
            'success' => true,
            'recipients' => $recipients,
            'total' => $recipients->count(),
            'stats' => [
                'avg_score' => $recipients->avg('interaction_score'),
                'avg_response_rate' => $recipients->avg('response_rate')
            ]
        ]);
    }

    public function storeBulkMessage(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:whatsapp_templates,id',
            'recipients' => 'required|string',
            'parameters' => 'nullable|array',
            'is_scheduled' => 'boolean',
            'scheduled_at' => 'required_if:is_scheduled,true|nullable|date|after:now',
            'target_category' => 'required|in:valuable,average,not_interested',
            'flow_id' => 'nullable|exists:whatsapp_message_flows,id'
        ]);

        $recipients = array_filter(array_map('trim', explode("\n", $request->recipients)));
        
        // Create bulk message
        $bulkMessage = WhatsappBulkMessage::create([
            'template_id' => $request->template_id,
            'recipients' => json_encode($recipients),
            'parameters' => $request->parameters ? json_encode($request->parameters) : null,
            'is_scheduled' => $request->is_scheduled ?? false,
            'scheduled_at' => $request->scheduled_at,
            'total_recipients' => count($recipients),
            'target_category' => $request->target_category,
            'flow_id' => $request->flow_id,
            'status' => 'pending'
        ]);

        // If a flow is specified, create flow steps for each recipient
        if ($request->flow_id) {
            $flow = WhatsappMessageFlow::find($request->flow_id);
            if ($flow) {
                foreach ($recipients as $phone) {
                    $this->createFlowStepsForRecipient($phone, $flow, $bulkMessage->id);
                }
            }
        }

        // Update recipient interaction metadata
        foreach ($recipients as $phone) {
            $leadScore = WhatsappLeadScore::firstOrCreate(['phone' => $phone]);
            $leadScore->updateScore([
                'type' => 'bulk_message',
                'score' => 0, // Initial score, will be updated based on response
                'bulk_message_id' => $bulkMessage->id
            ]);
        }

        // If not scheduled, send immediately
        if (!$bulkMessage->is_scheduled) {
            // Dispatch job to send immediately
            \App\Jobs\ProcessScheduledWhatsappMessages::dispatch($bulkMessage);
            
            return response()->json([
                'success' => true,
                'message' => 'Bulk message created and sending started',
                'id' => $bulkMessage->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk message scheduled successfully for ' . $bulkMessage->scheduled_at->setTimezone('Asia/Kolkata')->format('Y-m-d H:i') . ' IST',
            'id' => $bulkMessage->id
        ]);
    }

    private function createFlowStepsForRecipient($phone, $flow, $bulkMessageId)
    {
        $baseDelay = 0;
        foreach ($flow->flow_steps as $index => $step) {
            $baseDelay += $step['delay_hours'];
            
            DelayedResponseJob::dispatch(
                $phone,
                "flow_{$flow->id}_step_{$index}",
                $step['template_id'],
                $this->getFlowStepParams($phone, $step),
                null
            )->delay(now()->addHours($baseDelay));
        }
    }

    private function getFlowStepParams($phone, $step)
    {
        $leadScore = WhatsappLeadScore::where('phone', $phone)->first();
        
        return [
            'customer_name' => $leadScore->customer_name ?? 'Valued Customer',
            'last_interaction' => $leadScore->last_interaction ? $leadScore->last_interaction->diffForHumans() : 'recently',
            'interaction_count' => count($leadScore->interaction_history ?? []),
            'custom_data' => $step['custom_params'] ?? []
        ];
    }

    public function showBulkMessage($id)
    {
        $bulkMessage = WhatsappBulkMessage::with('template')->findOrFail($id);
        return response()->json($bulkMessage);
    }

    public function sendBulkMessage(Request $request)
    {
        $request->validate([
            'bulk_message_id' => 'required|exists:whatsapp_bulk_messages,id'
        ]);

        $bulkMessage = WhatsappBulkMessage::with('template')->findOrFail($request->bulk_message_id);
        
        if ($bulkMessage->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Message is not pending'
            ]);
        }

        // Update status to processing
        $bulkMessage->update(['status' => 'processing']);

        $recipients = json_decode($bulkMessage->recipients, true);
        $parameters = json_decode($bulkMessage->parameters, true) ?? [];
        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($recipients as $phone) {
            try {
                // Get the actual template parameters from the stored template
                $templateParameters = $bulkMessage->template->parameters ?? [];
                
                // Only send parameters if the template actually expects them
                $templateExpectsParameters = !empty($templateParameters);
                
                $dynamicParams = [];
                if ($templateExpectsParameters && !empty($parameters)) {
                    $dynamicParams = $this->prepareDynamicParameters($phone, $parameters);
                }
                
                $response = $this->whatsappService->sendMessage(
                    $phone,
                    null, // No direct message
                    $bulkMessage->template->name,
                    $dynamicParams
                );

                if ($response['success']) {
                    $sent++;
                    
                    // Record conversation
                    WhatsappConversation::create([
                        'phone' => $phone,
                        'message' => $bulkMessage->template->content,
                        'direction' => 'outbound',
                        'response_type' => 'template',
                        'template_id' => $bulkMessage->template_id,
                        'bulk_message_id' => $bulkMessage->id
                    ]);

                    // Update lead score
                    $leadScore = WhatsappLeadScore::firstOrCreate(['phone' => $phone]);
                    $leadScore->updateScore([
                        'type' => 'template_sent',
                        'score' => 1
                    ]);
                } else {
                    $failed++;
                    $errors[] = "Failed to send to {$phone}: " . ($response['error'] ?? 'Unknown error');
                }

                // Add delay between messages to avoid rate limiting
                usleep(500000); // 0.5 second delay
                
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Error sending to {$phone}: " . $e->getMessage();
                Log::error('Bulk message send error', [
                    'phone' => $phone,
                    'bulk_message_id' => $bulkMessage->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Update bulk message with results
        $bulkMessage->update([
            'status' => $failed === 0 ? 'completed' : 'failed',
            'sent_count' => $sent,
            'failed_count' => $failed,
            'error_details' => json_encode($errors),
            'sent_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => "Bulk message sent. {$sent} successful, {$failed} failed.",
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors
        ]);
    }

    private function prepareDynamicParameters($phone, $baseParameters)
    {
        $leadScore = WhatsappLeadScore::where('phone', $phone)->first();
        $conversation = WhatsappConversation::where('phone', $phone)->orderBy('created_at', 'desc')->first();

        $dynamicParams = $baseParameters;

        // Only add dynamic parameters if we have base parameters to work with
        // This prevents sending unexpected parameters to templates that don't expect any
        if (!empty($baseParameters)) {
            // Add dynamic parameters based on user data
            if ($leadScore) {
                $dynamicParams['customer_name'] = $leadScore->customer_name ?? 'Valued Customer';
                $dynamicParams['interaction_count'] = $leadScore->interaction_count ?? 0;
                $dynamicParams['last_interaction'] = $leadScore->last_interaction ? 
                    $leadScore->last_interaction->diffForHumans() : 'recently';
            }

            if ($conversation) {
                $dynamicParams['last_message'] = substr($conversation->message, 0, 50);
            }
        }

        return $dynamicParams;
    }

    public function cancelBulkMessage(Request $request, $id)
    {
        $bulkMessage = WhatsappBulkMessage::findOrFail($id);
        
        // Check if this is a delete request
        if ($request->input('action') === 'delete') {
            // Only allow deletion of cancelled, failed, or completed messages
            if (in_array($bulkMessage->status, ['cancelled', 'failed', 'completed'])) {
                $bulkMessage->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Bulk message deleted successfully'
                ]);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete active or pending messages. Cancel the message first.'
            ]);
        }
        
        // Default cancel behavior
        if (in_array($bulkMessage->status, ['pending', 'processing'])) {
            $bulkMessage->update([
                'status' => 'failed',
                'cancelled_at' => now()
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Bulk message cancelled successfully'
            ]);
        }
        
        return response()->json([
            'success' => false, 
            'message' => 'Message cannot be cancelled'
        ]);
    }

    public function deleteBulkMessage($id)
    {
        try {
            $bulkMessage = WhatsappBulkMessage::findOrFail($id);
            
            // Only allow deletion of cancelled, failed, or completed messages
            if (in_array($bulkMessage->status, ['cancelled', 'failed', 'completed'])) {
                $bulkMessage->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Bulk message deleted successfully'
                ]);
            }
            
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete active or pending messages. Cancel the message first.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting bulk message: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMessageStatusForConsole()
    {
        try {
            // Get recent messages that might have status updates
            $messages = WhatsappBulkMessage::where('created_at', '>=', now()->subHours(2))
                ->whereIn('status', ['processing', 'completed', 'failed'])
                ->select('id', 'status', 'sent_count', 'failed_count', 'total_recipients', 'error_details', 'updated_at')
                ->orderBy('updated_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting message status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRealTimeStats()
    {
        try {
            Log::info('Real-time stats endpoint called', [
                'user_id' => auth('admin')->id(),
                'timestamp' => now(),
                'method' => request()->method(),
                'ip' => request()->ip()
            ]);
            
            // Get all bulk messages
            $allMessages = WhatsappBulkMessage::with('template')->get();
            
            // Calculate real-time statistics
            $totalSent = $allMessages->sum('sent_count');
            $totalFailed = $allMessages->sum('failed_count');
            $totalRecipients = $allMessages->sum('total_recipients');
            
            // Calculate success rate
            $successRate = $totalRecipients > 0 ? round(($totalSent / $totalRecipients) * 100, 1) : 0;
            
            // Get pending messages count
            $pendingCount = $allMessages->where('status', 'pending')->count();
            
            // Get this month's messages count
            $thisMonthCount = WhatsappBulkMessage::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('sent_count');
            
            // Get table data with real-time status
            $tableMessages = WhatsappBulkMessage::with('template')
                ->orderBy('created_at', 'desc')
                ->take(50) // Limit for performance
                ->get()
                ->map(function($message) {
                    return [
                        'id' => $message->id,
                        'template_name' => $message->template->name ?? 'N/A',
                        'total_recipients' => $message->total_recipients,
                        'status' => $message->status,
                        'sent_count' => $message->sent_count ?? 0,
                        'failed_count' => $message->failed_count ?? 0,
                        'is_scheduled' => $message->is_scheduled,
                        'scheduled_at' => $message->scheduled_at ? 
                            $message->scheduled_at->setTimezone('Asia/Kolkata')->format('Y-m-d H:i') . ' IST' : '-',
                        'created_at' => $message->created_at->setTimezone('Asia/Kolkata')->format('Y-m-d H:i') . ' IST',
                        'can_send' => $message->status === 'pending',
                        'can_cancel' => $message->status === 'pending',
                        'can_duplicate' => $message->status === 'completed'
                    ];
                });

            $response = [
                'success' => true,
                'stats' => [
                    'total_sent' => $totalSent,
                    'success_rate' => $successRate,
                    'pending_count' => $pendingCount,
                    'this_month_count' => $thisMonthCount
                ],
                'table_data' => $tableMessages,
                'last_updated' => now()->format('H:i:s')
            ];
            
            Log::info('Real-time stats response prepared', [
                'stats_count' => count($response['stats']), 
                'table_count' => count($tableMessages),
                'total_sent' => $totalSent,
                'success_rate' => $successRate
            ]);
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Real-time stats error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch real-time statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportBulkMessages()
    {
        $bulkMessages = WhatsappBulkMessage::with('template')->get();
        
        $csvData = [];
        $csvData[] = ['ID', 'Template', 'Recipients Count', 'Status', 'Sent', 'Failed', 'Created At'];
        
        foreach ($bulkMessages as $message) {
            $csvData[] = [
                $message->id,
                $message->template->name ?? 'N/A',
                $message->total_recipients,
                $message->status,
                $message->sent_count,
                $message->failed_count,
                $message->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        $filename = 'bulk-messages-export-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w');
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function exportBulkMessage($id)
    {
        $bulkMessage = WhatsappBulkMessage::with('template')->findOrFail($id);
        $recipients = json_decode($bulkMessage->recipients, true);
        
        $csvData = [];
        $csvData[] = ['Phone Number', 'Status', 'Template', 'Created At'];
        
        // Get conversation records for this bulk message
        $conversations = WhatsappConversation::where('bulk_message_id', $id)->get()->keyBy('phone');
        
        foreach ($recipients as $phone) {
            $conversation = $conversations->get($phone);
            $status = $conversation ? 'Sent' : 'Pending';
            
            $csvData[] = [
                $phone,
                $status,
                $bulkMessage->template->name ?? 'N/A',
                $bulkMessage->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        $filename = 'bulk-message-' . $id . '-export.csv';
        $handle = fopen('php://temp', 'w');
        
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function getBulkMessageAnalytics()
    {
        $analytics = WhatsappBulkMessage::with('template')
            ->selectRaw('
                template_id,
                COUNT(*) as total_campaigns,
                SUM(sent_count) as total_sent,
                SUM(failed_count) as total_failed,
                AVG(sent_count / total_recipients * 100) as avg_success_rate
            ')
            ->groupBy('template_id')
            ->orderBy('total_sent', 'desc')
            ->get();

        $bestTemplate = $analytics->first();
        
        return response()->json([
            'success' => true,
            'analytics' => $analytics,
            'best_template' => $bestTemplate->template->name ?? 'None',
            'total_campaigns' => WhatsappBulkMessage::count(),
            'total_messages_sent' => WhatsappBulkMessage::sum('sent_count')
        ]);
    }

    public function getSmartRecipients(Request $request)
    {
        $request->validate([
            'category' => 'required|in:valuable,average,not_interested',
            'min_score' => 'nullable|numeric|min:0|max:10',
            'last_interaction_days' => 'nullable|numeric|min:1',
            'exclude_recent_bulk' => 'boolean'
        ]);

        try {
            $query = WhatsappLeadScore::query();

            // Filter by category
            switch ($request->category) {
                case 'valuable':
                    $query->where('score', '>=', 7);
                    break;
                case 'average':
                    $query->whereBetween('score', [4, 6.9]);
                    break;
                case 'not_interested':
                    $query->where('score', '<', 4);
                    break;
            }

            // Apply additional filters
            if ($request->min_score) {
                $query->where('score', '>=', $request->min_score);
            }

            if ($request->last_interaction_days) {
                $query->where('last_interaction', '>=', now()->subDays($request->last_interaction_days));
            }

            if ($request->exclude_recent_bulk) {
                // Exclude numbers that received bulk messages in last 7 days
                $recentRecipients = WhatsappBulkMessage::where('created_at', '>=', now()->subDays(7))
                    ->get()
                    ->pluck('recipients')
                    ->flatten()
                    ->map(function($recipients) {
                        return json_decode($recipients, true);
                    })
                    ->flatten()
                    ->unique()
                    ->values();

                $query->whereNotIn('phone', $recentRecipients);
            }

            $recipients = $query->limit(1000)->get();

            return response()->json([
                'success' => true,
                'recipients' => $recipients->map(function($recipient) {
                    return [
                        'phone' => $recipient->phone,
                        'name' => $recipient->name ?? 'Unknown',
                        'score' => $recipient->score,
                        'category' => $recipient->score >= 7 ? 'valuable' : ($recipient->score >= 4 ? 'average' : 'not_interested')
                    ];
                }),
                'total' => $recipients->count(),
                'stats' => [
                    'avg_score' => $recipients->avg('score'),
                    'max_score' => $recipients->max('score'),
                    'min_score' => $recipients->min('score')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting smart recipients: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading recipients: ' . $e->getMessage()
            ], 500);
        }
    }

    // Auto Replies
    public function showAutoReplies()
    {
        $autoReplies = WhatsappAutoReply::with(['template', 'chatgptPrompt'])
            ->orderBy('created_at', 'desc')
            ->get();
        $templates = WhatsappTemplate::all();
        return view('admin.pages.whatsapp.auto-replies', compact('autoReplies', 'templates'));
    }

    public function storeAutoReply(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
            'reply_type' => 'required|in:text,template,gpt',
            'reply_content' => 'required_if:reply_type,text|nullable|string',
            'template_id' => 'required_if:reply_type,template|nullable|exists:whatsapp_templates,id',
            'gpt_prompt' => 'required_if:reply_type,gpt|nullable|string',
            'sentiment_type' => 'nullable|in:positive,negative,neutral',
            'priority' => 'nullable|integer|min:1|max:10',
            'follow_up_template_id' => 'nullable|exists:whatsapp_templates,id',
            'follow_up_delay' => 'nullable|integer', // delay in minutes
        ]);

        $autoReply = WhatsappAutoReply::create(array_merge($request->all(), [
            'is_active' => true,
            'smart_selection' => true, // Enable smart template selection
        ]));
        return response()->json(['success' => true, 'id' => $autoReply->id]);
    }

    public function showAutoReply($id)
    {
        $autoReply = WhatsappAutoReply::findOrFail($id);
        return response()->json($autoReply);
    }

    public function updateAutoReply(Request $request, $id)
    {
        $autoReply = WhatsappAutoReply::findOrFail($id);
        $request->validate([
            'keyword' => 'required|string',
            'reply_type' => 'required|in:text,template,gpt',
            'reply_content' => 'required_if:reply_type,text|nullable|string',
            'template_id' => 'required_if:reply_type,template|nullable|exists:whatsapp_templates,id',
            'gpt_prompt' => 'required_if:reply_type,gpt|nullable|string',
        ]);

        $autoReply->update($request->all());
        return response()->json(['success' => true]);
    }

    public function deleteAutoReply($id)
    {
        $autoReply = WhatsappAutoReply::findOrFail($id);
        $autoReply->delete();
        return response()->json(['success' => true]);
    }

    // Templates - Only show approved templates from Meta API
    public function showTemplates()
    {
        try {
            // Fetch approved templates from WhatsApp API
            $approvedTemplates = $this->whatsappService->fetchTemplates(true);
            
            // Get campaigns for linking
            $campaigns = \App\Models\Campaign::where('approval_status', 'approved')
                                           ->with('doctor', 'category')
                                           ->get();
            
            // Get existing template-campaign mappings
            $templateCampaigns = WhatsappTemplateCampaign::with(['whatsappTemplate', 'campaign'])
                                                       ->where('is_active', true)
                                                       ->get();

            return view('admin.pages.whatsapp.templates', compact('approvedTemplates', 'campaigns', 'templateCampaigns'));
        } catch (\Exception $e) {
            Log::error('Templates page error', ['error' => $e->getMessage()]);
            
            // Fallback to empty arrays if API fails
            $approvedTemplates = [];
            $campaigns = \App\Models\Campaign::where('approval_status', 'approved')
                                           ->with('doctor', 'category')
                                           ->get();
            $templateCampaigns = [];
            
            return view('admin.pages.whatsapp.templates', compact('approvedTemplates', 'campaigns', 'templateCampaigns'))
                ->with('error', 'Failed to load templates: ' . $e->getMessage());
        }
    }

    public function linkTemplateToCampaign(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:whatsapp_templates,id',
            'campaign_id' => 'required|exists:campaigns,id',
            'trigger_event' => 'required|in:registration,reminder,follow_up,confirmation,cancellation',
            'dynamic_params' => 'required|array',
            'delay_minutes' => 'integer|min:0',
            'conditions' => 'nullable|array'
        ]);

        $templateCampaign = WhatsappTemplateCampaign::updateOrCreate(
            [
                'whatsapp_template_id' => $request->template_id,
                'campaign_id' => $request->campaign_id,
                'trigger_event' => $request->trigger_event
            ],
            [
                'dynamic_params' => $request->dynamic_params,
                'delay_minutes' => $request->delay_minutes ?? 0,
                'conditions' => $request->conditions,
                'is_active' => true
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Template linked to campaign successfully',
            'id' => $templateCampaign->id
        ]);
    }

    public function unlinkTemplateCampaign($id)
    {
        $templateCampaign = WhatsappTemplateCampaign::findOrFail($id);
        $templateCampaign->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Template unlinked from campaign'
        ]);
    }

    public function sendCampaignTemplate(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'phone' => 'required|string',
            'trigger_event' => 'required|string',
            'additional_data' => 'nullable|array'
        ]);

        $campaign = \App\Models\Campaign::with('doctor', 'category')->findOrFail($request->campaign_id);
        $phone = $request->phone;
        $triggerEvent = $request->trigger_event;
        $additionalData = $request->additional_data ?? [];

        // Find active template mapping for this campaign and trigger
        $templateCampaign = WhatsappTemplateCampaign::with('whatsappTemplate')
                                                  ->where('campaign_id', $campaign->id)
                                                  ->where('trigger_event', $triggerEvent)
                                                  ->where('is_active', true)
                                                  ->first();

        if (!$templateCampaign) {
            return response()->json([
                'success' => false,
                'message' => 'No active template found for this campaign and trigger event'
            ]);
        }

        // Check if template should be sent based on conditions
        if (!$templateCampaign->shouldSend($campaign, $additionalData)) {
            return response()->json([
                'success' => false,
                'message' => 'Template conditions not met'
            ]);
        }

        // Map campaign data to template parameters
        $templateParams = $templateCampaign->mapCampaignData($campaign, $additionalData);

        try {
            // Send the template message
            $response = $this->whatsappService->sendMessage(
                $phone,
                null,
                $templateCampaign->whatsappTemplate->name,
                $templateParams
            );

            if ($response['success']) {
                // Log the conversation
                WhatsappConversation::create([
                    'phone' => $phone,
                    'message' => $templateCampaign->whatsappTemplate->content,
                    'direction' => 'outbound',
                    'response_type' => 'template',
                    'template_id' => $templateCampaign->whatsapp_template_id,
                    'campaign_id' => $campaign->id,
                    'trigger_event' => $triggerEvent
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Campaign template sent successfully',
                    'message_id' => $response['message_id'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send template: ' . ($response['error'] ?? 'Unknown error')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Campaign template send error', [
                'campaign_id' => $campaign->id,
                'phone' => $phone,
                'trigger_event' => $triggerEvent,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error sending template: ' . $e->getMessage()
            ]);
        }
    }

    public function refreshTemplates()
    {
        try {
            // Check if WhatsApp credentials are configured
            $apiKey = config('services.whatsapp.token');
            $businessAccountId = config('services.whatsapp.business_account_id');
            
            if (!$apiKey || !$businessAccountId) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp API credentials not configured. Please check your .env file.'
                ], 400);
            }
            
            // Fetch fresh templates from Meta API (approved only)
            $approvedTemplates = $this->whatsappService->fetchTemplates(true, true);
            
            return response()->json([
                'success' => true,
                'message' => 'Templates refreshed successfully',
                'count' => count($approvedTemplates)
            ]);
        } catch (\Exception $e) {
            Log::error('Template refresh error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh templates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testConnection()
    {
        try {
            $apiKey = config('services.whatsapp.token');
            $businessAccountId = config('services.whatsapp.business_account_id');
            
            return response()->json([
                'success' => true,
                'message' => 'WhatsApp API configuration test',
                'config' => [
                    'api_key_configured' => !empty($apiKey),
                    'business_account_id_configured' => !empty($businessAccountId),
                    'api_key_preview' => $apiKey ? substr($apiKey, 0, 10) . '...' : 'Not set',
                    'business_account_id' => $businessAccountId ?: 'Not set'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Configuration error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTemplateCampaignPreview(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:whatsapp_templates,id',
            'campaign_id' => 'required|exists:campaigns,id',
            'dynamic_params' => 'required|array'
        ]);

        $template = WhatsappTemplate::findOrFail($request->template_id);
        $campaign = \App\Models\Campaign::with('doctor', 'category')->findOrFail($request->campaign_id);

        // Create a temporary mapping to preview
        $tempMapping = new WhatsappTemplateCampaign([
            'dynamic_params' => $request->dynamic_params
        ]);

        // Map the data
        $mappedParams = $tempMapping->mapCampaignData($campaign);

        // Replace placeholders in template content
        $previewContent = $template->content;
        foreach ($mappedParams as $param => $value) {
            $previewContent = str_replace("{{$param}}", $value, $previewContent);
        }

        return response()->json([
            'success' => true,
            'preview' => $previewContent,
            'mapped_params' => $mappedParams,
            'template_name' => $template->name
        ]);
    }

    protected function getTemplateStatus($template)
    {
        if (!$template->whatsapp_id) {
            return 'not_synced';
        }
        
        try {
            $status = Cache::remember('template_status_' . $template->whatsapp_id, 3600, function () use ($template) {
                $response = Http::withToken(config('services.whatsapp.token'))
                    ->get("https://graph.facebook.com/v19.0/{$template->whatsapp_id}");
                
                if ($response->successful()) {
                    return $response->json()['status'];
                }
                return 'unknown';
            });
            
            return $status;
        } catch (\Exception $e) {
            Log::error('Failed to get template status: ' . $e->getMessage());
            return 'unknown';
        }
    }

    public function syncTemplates()
    {
        try {
            // Fetch fresh templates from Meta API and sync with database
            $templates = $this->whatsappService->fetchTemplates(false, true);
            
            return response()->json([
                'success' => true,
                'count' => count($templates),
                'message' => "Successfully synced " . count($templates) . " templates"
            ]);
        } catch (\Exception $e) {
            Log::error('Template sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync templates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function testTemplate(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:whatsapp_templates,id',
            'phone' => 'required|string',
            'parameters' => 'nullable|array'
        ]);

        try {
            $template = WhatsappTemplate::findOrFail($request->template_id);
            $service = app(WhatsappCloudApiService::class);

            $response = $service->sendTemplate(
                $request->phone,
                $template->name,
                $this->prepareTemplateComponents($request->parameters ?? [])
            );

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test message sent successfully',
                    'message_id' => $response['message_id']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['error'] ?? 'Failed to send test message'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Test template failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test message: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function prepareTemplateComponents($parameters)
    {
        if (empty($parameters)) {
            return [];
        }

        return [
            [
                'type' => 'body',
                'parameters' => array_map(function($param) {
                    if (is_array($param)) {
                        return [
                            'type' => $param['type'] ?? 'text',
                            'text' => $param['value'] ?? ''
                        ];
                    }
                    return [
                        'type' => 'text',
                        'text' => (string)$param
                    ];
                }, $parameters)
            ]
        ];
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'parameters' => 'nullable|array',
            'language' => 'required|string|size:2',
        ]);

        $template = WhatsappTemplate::create([
            'name' => $request->name,
            'content' => $request->content,
            'parameters' => $request->parameters ? json_encode($request->parameters) : null,
            'language' => $request->language,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'id' => $template->id]);
    }

    public function showTemplate($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        return response()->json($template);
    }

    public function updateTemplate(Request $request, $id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'parameters' => 'nullable|array',
            'language' => 'required|string|size:2',
        ]);

        $template->update([
            'name' => $request->name,
            'content' => $request->content,
            'parameters' => $request->parameters ? json_encode($request->parameters) : null,
            'language' => $request->language,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteTemplate($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        $template->delete();
        return response()->json(['success' => true]);
    }

    // ChatGPT Integration
    public function showChatGPT()
    {
        $prompts = WhatsappChatGPTPrompt::orderBy('created_at', 'desc')->get();
        $config = [
            'openai_api_key' => Config::get('services.openai.api_key'),
            'openai_model' => Config::get('services.openai.model'),
            'temperature' => Config::get('services.openai.temperature'),
            'max_tokens' => Config::get('services.openai.max_tokens'),
        ];
        return view('admin.pages.whatsapp.chatgpt', compact('prompts', 'config'));
    }

    public function saveChatGPTConfig(Request $request)
    {
        $request->validate([
            'openai_api_key' => 'required|string',
            'openai_model' => 'required|in:gpt-4,gpt-3.5-turbo',
            'temperature' => 'required|numeric|min:0|max:1',
            'max_tokens' => 'required|integer|min:100|max:4000',
        ]);

        // Update .env file or configuration
        $this->updateEnvFile([
            'OPENAI_API_KEY' => $request->openai_api_key,
            'OPENAI_MODEL' => $request->openai_model,
            'OPENAI_TEMPERATURE' => $request->temperature,
            'OPENAI_MAX_TOKENS' => $request->max_tokens,
        ]);

        return response()->json(['success' => true]);
    }

    public function storeChatGPTPrompt(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prompt_template' => 'required|string',
            'system_message' => 'nullable|string',
            'allowed_parameters' => 'nullable|array',
        ]);

        $prompt = WhatsappChatGPTPrompt::create([
            'name' => $request->name,
            'prompt_template' => $request->prompt_template,
            'system_message' => $request->system_message,
            'allowed_parameters' => $request->allowed_parameters ? json_encode($request->allowed_parameters) : null,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'id' => $prompt->id]);
    }

    public function showChatGPTPrompt($id)
    {
        $prompt = WhatsappChatGPTPrompt::findOrFail($id);
        return response()->json($prompt);
    }

    public function updateChatGPTPrompt(Request $request, $id)
    {
        $prompt = WhatsappChatGPTPrompt::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'prompt_template' => 'required|string',
            'system_message' => 'nullable|string',
            'allowed_parameters' => 'nullable|array',
        ]);

        $prompt->update([
            'name' => $request->name,
            'prompt_template' => $request->prompt_template,
            'system_message' => $request->system_message,
            'allowed_parameters' => $request->allowed_parameters ? json_encode($request->allowed_parameters) : null,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteChatGPTPrompt($id)
    {
        $prompt = WhatsappChatGPTPrompt::findOrFail($id);
        $prompt->delete();
        return response()->json(['success' => true]);
    }

    public function conversationHistory(Request $request)
    {
        $query = WhatsappConversation::with(['template', 'autoReply', 'chatgptPrompt'])
            ->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->has('response_type')) {
            $query->where('response_type', $request->response_type);
        }

        $conversations = $query->paginate(20);

        return view('admin.pages.whatsapp.conversation-history', compact('conversations'));
    }

    public function processIncomingMessage(Request $request)
    {
        $message = $request->input('message');
        $phone = $request->input('phone');
        $messageId = $request->input('message_id');
        $mediaUrl = $request->input('media_url');
        $mediaType = $request->input('media_type');
        
        // Get or create lead score
        $leadScore = WhatsappLeadScore::firstOrCreate(
            ['phone' => $phone],
            ['category' => 'average', 'interaction_score' => 0]
        );

        // Handle image processing if media is present
        if ($mediaUrl && $mediaType === 'image') {
            $this->processIncomingImage($mediaUrl, $phone, $messageId);
        }

        // Create conversation record
        $conversation = WhatsappConversation::create([
            'phone' => $phone,
            'message' => $message,
            'message_id' => $messageId,
            'is_responded' => false
        ]);
        
        // Try to match with default responses first
        $defaultResponse = $this->findDefaultResponse($message);
        if ($defaultResponse) {
            $response = $this->handleDefaultResponse($defaultResponse, $phone, $message, $leadScore);
        } else {
            // Try auto-replies next
            $autoReply = $this->findMatchingAutoReply($message);
            if ($autoReply) {
                $response = $this->handleDelayedAutoReply($autoReply, $phone, $message, $messageId, $conversation->id);
            } else {
                // Use ChatGPT with context as last resort
                $response = $this->handleWithSmartChatGPT($phone, $message, $leadScore);
            }
        }

        // Update lead score and status
        $this->updateLeadInteraction($leadScore, $message, $response);

        // Process message flow based on lead category
        $this->processMessageFlow($leadScore, $response);

        // Schedule category-based follow-up
        $this->scheduleCategoryBasedFollowUp($leadScore, $response);

        return response()->json($response);
    }

    private function handleDelayedAutoReply($autoReply, $phone, $message, $messageId, $conversationId)
    {
        $delay = $this->calculateResponseDelay($autoReply, $message);
        
        if ($delay > 0) {
            // Schedule delayed response
            DelayedResponseJob::dispatch(
                $phone,
                $messageId,
                $autoReply->template_id,
                $this->prepareTemplateParams($message),
                $conversationId
            )->delay(now()->addSeconds($delay));

            return [
                'status' => 'pending',
                'delay' => $delay,
                'type' => 'delayed_auto_reply'
            ];
        }

        // Immediate response
        return $this->handleAutoReply($autoReply, $phone, $message);
    }

    private function calculateResponseDelay($autoReply, $message)
    {
        if (!$autoReply->use_smart_delay) {
            return $autoReply->min_delay_seconds ?? 30;
        }

        // Get message length and complexity
        $messageLength = strlen($message);
        $wordCount = str_word_count($message);
        
        // Calculate base delay based on message length
        $baseDelay = min(
            $autoReply->max_delay_seconds,
            max(
                $autoReply->min_delay_seconds,
                intval($wordCount * 1.5) // 1.5 seconds per word
            )
        );

        // Adjust delay based on time of day
        $hour = now()->hour;
        if ($hour >= 22 || $hour <= 6) {
            $baseDelay = min($baseDelay * 1.5, $autoReply->max_delay_seconds);
        }

        // Add random variation (±20%)
        $variation = $baseDelay * 0.2;
        return rand(
            intval($baseDelay - $variation),
            intval($baseDelay + $variation)
        );
    }

    private function prepareTemplateParams($message)
    {
        // Extract relevant parameters from message
        // This is a basic implementation - enhance based on your needs
        return [
            'customer_message' => $message,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];
    }

    private function findMatchingAutoReply($message)
    {
        // Get all active auto-replies ordered by priority
        $autoReplies = WhatsappAutoReply::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($autoReplies as $reply) {
            // Check for keyword match
            if (stripos($message, $reply->keyword) !== false) {
                return $reply;
            }
        }

        return null;
    }

    private function updateLeadStatus($phone, $message, $response)
    {
        $conversation = WhatsappConversation::where('phone', $phone)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$conversation) {
            return;
        }

        // Analyze sentiment and update lead status
        $sentiment = $this->analyzeSentiment($message);
        $positiveCount = WhatsappConversation::where('phone', $phone)
            ->where('sentiment', 'positive')
            ->count();

        // Update lead status based on interaction patterns
        if ($positiveCount >= 3) {
            $leadStatus = 'hot';
        } elseif ($sentiment === 'positive') {
            $leadStatus = 'warm';
        } elseif ($sentiment === 'negative') {
            $leadStatus = 'cold';
        } else {
            $leadStatus = $conversation->lead_status ?? 'new';
        }

        $conversation->update([
            'sentiment' => $sentiment,
            'lead_status' => $leadStatus,
            'last_interaction' => now(),
        ]);
    }

    private function analyzeSentiment($message)
    {
        // Define positive and negative keywords
        $positiveKeywords = [
            'yes', 'interested', 'good', 'great', 'awesome', 'sure', 'okay', 
            'ok', 'thank', 'please', 'want', 'like', 'book', 'appointment'
        ];
        
        $negativeKeywords = [
            'no', 'not', 'don\'t', 'dont', 'busy', 'later', 'expensive', 
            'bad', 'worse', 'worst', 'stop', 'unsubscribe'
        ];

        $message = strtolower($message);
        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($positiveKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $positiveCount++;
            }
        }

        foreach ($negativeKeywords as $keyword) {
            if (stripos($message, $keyword) !== false) {
                $negativeCount++;
            }
        }

        if ($positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount) {
            return 'negative';
        }

        return 'neutral';
    }

    private function scheduleFollowUp($phone, $response)
    {
        // Check if the response indicates need for follow-up
        if ($response['sentiment'] === 'positive' || $response['lead_status'] === 'hot') {
            // Schedule follow-up after 24 hours
            FollowUpJob::dispatch($phone, $response['lead_status'])
                ->delay(now()->addHours(24));
        }
    }

    private function findDefaultResponse($message)
    {
        return WhatsappDefaultResponse::all()->first(function ($response) use ($message) {
            return preg_match("/{$response->question_pattern}/i", $message);
        });
    }

    private function handleDefaultResponse($defaultResponse, $phone, $message, $leadScore)
    {
        if ($defaultResponse->template_id) {
            $template = WhatsappTemplate::find($defaultResponse->template_id);
            if ($template) {
                $params = $this->extractTemplateParams($message, $defaultResponse->parameters);
                $service = new WhatsappCloudApiService();
                $response = $service->sendMessage($phone, '', $template->name, $params);
                
                $wasSuccessful = isset($response['messages']);
                $defaultResponse->updateStats($wasSuccessful);
                
                return [
                    'type' => 'template',
                    'success' => $wasSuccessful,
                    'template' => $template->name,
                    'score_impact' => 5
                ];
            }
        }

        return [
            'type' => 'text',
            'message' => $defaultResponse->answer,
            'score_impact' => 3
        ];
    }

    private function handleWithSmartChatGPT($phone, $message, $leadScore)
    {
        // Get relevant context based on lead category and message content
        $context = $this->getRelevantContext($message, $leadScore->category);
        
        if (!$context) {
            return $this->handleWithChatGPT($phone, $message);
        }

        $messages = [
            ['role' => 'system', 'content' => $context->system_message],
            ['role' => 'user', 'content' => $message]
        ];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 300
        ]);

        return [
            'type' => 'chatgpt',
            'message' => $response->choices[0]->message->content,
            'context_used' => $context->topic,
            'score_impact' => 2
        ];
    }

    private function updateLeadInteraction($leadScore, $message, $response)
    {
        // Base score from response type
        $scoreImpact = $response['score_impact'] ?? 1;

        // Analyze message sentiment
        $sentiment = $this->analyzeSentiment($message);
        if ($sentiment === 'positive') {
            $scoreImpact += 2;
        } elseif ($sentiment === 'negative') {
            $scoreImpact -= 1;
        }

        // Check for key phrases
        if (preg_match('/\b(book|appointment|schedule|interested|buy|purchase)\b/i', $message)) {
            $scoreImpact += 3;
        }

        // Update lead score
        $leadScore->updateScore([
            'type' => $response['type'],
            'score' => $scoreImpact
        ]);
    }

    private function processMessageFlow($leadScore, $response)
    {
        $activeFlow = WhatsappMessageFlow::where('target_category', $leadScore->category)
            ->where('is_active', true)
            ->first();

        if (!$activeFlow) {
            return;
        }

        // Get current step or start new flow
        $history = $leadScore->interaction_history ?? [];
        $currentStep = count($history) % count($activeFlow->flow_steps);

        // Schedule next step in flow
        $nextStep = $activeFlow->flow_steps[$currentStep];
        if ($nextStep['delay_hours'] > 0) {
            $this->scheduleFlowStep($leadScore->phone, $nextStep, $activeFlow->id);
        }
    }

    private function scheduleCategoryBasedFollowUp($leadScore, $response)
    {
        $delays = [
            'valuable' => 24, // Follow up after 24 hours
            'average' => 48,  // Follow up after 48 hours
            'not_interested' => 168 // Follow up after 1 week
        ];

        $delay = $delays[$leadScore->category] ?? 48;
        
        FollowUpJob::dispatch($leadScore->phone, $leadScore->category)
            ->delay(now()->addHours($delay));
    }

    private function getRelevantContext($message, $category)
    {
        return WhatsappChatGPTContext::where('is_active', true)
            ->get()
            ->first(function ($context) use ($message) {
                $keywords = collect($context->context_data['keywords'] ?? []);
                return $keywords->contains(function ($keyword) use ($message) {
                    return stripos($message, $keyword) !== false;
                });
            });
    }

    private function extractTemplateParams($message, $parameterRules)
    {
        $params = [];
        foreach ($parameterRules as $param => $pattern) {
            if (preg_match($pattern, $message, $matches)) {
                $params[$param] = $matches[1] ?? '';
            }
        }
        return $params;
    }

    private function processIncomingImage($mediaUrl, $phone, $messageId)
    {
        // Download the image
        $imageContents = file_get_contents($mediaUrl);
        $fileName = 'whatsapp_images/' . uniqid() . '.jpg';
        $path = storage_path('app/public/' . $fileName);
        
        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        // Save the image
        file_put_contents($path, $imageContents);

        // Create media record
        $media = WhatsappMedia::create([
            'phone' => $phone,
            'message_id' => $messageId,
            'media_type' => 'image',
            'file_path' => $fileName,
            'processed_at' => now()
        ]);

        // Broadcast new image event for real-time notification
        event(new WhatsappImageReceived([
            'id' => $media->id,
            'phone' => $phone,
            'url' => asset('storage/' . $fileName),
            'received_at' => now()->toIso8601String()
        ]));

        // Optional: Process image with AI for content detection
        try {
            $imageAnalysis = $this->analyzeImageContent($path);
            $media->update([
                'analysis_data' => $imageAnalysis,
                'analyzed_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Image analysis failed: ' . $e->getMessage());
        }

        return $media;
    }

    private function analyzeImageContent($imagePath)
    {
        // Use OpenAI's Vision API to analyze image content
        try {
            $response = OpenAI::images()->analyze([
                'model' => 'gpt-4-vision-preview',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image',
                                'source' => [
                                    'type' => 'path',
                                    'path' => $imagePath
                                ]
                            ],
                            [
                                'type' => 'text',
                                'text' => 'What is in this image? Provide a brief description and identify any important elements.'
                            ]
                        ]
                    ]
                ]
            ]);

            return [
                'description' => $response->choices[0]->message->content,
                'analyzed_at' => now()->toIso8601String()
            ];
        } catch (\Exception $e) {
            \Log::error('OpenAI Vision API error: ' . $e->getMessage());
            return null;
        }
    }

    private function updateEnvFile($data)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $content = file_get_contents($path);
            foreach ($data as $key => $value) {
                if (str_contains($content, $key.'=')) {
                    $content = preg_replace(
                        "/^{$key}=.*/m",
                        "{$key}={$value}",
                        $content
                    );
                } else {
                    $content .= "\n{$key}={$value}";
                }
            }
            file_put_contents($path, $content);
        }
    }

    public function showSettings()
    {
        $settings = [
            'whatsapp_api_key' => config('services.whatsapp.api_key'),
            'whatsapp_phone_number_id' => config('services.whatsapp.phone_number_id'),
            'whatsapp_business_account_id' => config('services.whatsapp.business_account_id'),
            'auto_reply_delay' => config('services.whatsapp.auto_reply_delay', 30),
            'max_daily_messages' => config('services.whatsapp.max_daily_messages', 1000),
            'notification_email' => config('services.whatsapp.notification_email'),
        ];

        return view('admin.pages.whatsapp.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'whatsapp_api_key' => 'required|string',
            'whatsapp_phone_number_id' => 'required|string',
            'whatsapp_business_account_id' => 'required|string',
            'auto_reply_delay' => 'required|integer|min:0|max:300',
            'max_daily_messages' => 'required|integer|min:1|max:10000',
            'notification_email' => 'required|email',
        ]);

        // Update .env file with new settings
        $this->updateEnvFile([
            'WHATSAPP_API_KEY' => $request->whatsapp_api_key,
            'WHATSAPP_PHONE_NUMBER_ID' => $request->whatsapp_phone_number_id,
            'WHATSAPP_BUSINESS_ACCOUNT_ID' => $request->whatsapp_business_account_id,
            'WHATSAPP_AUTO_REPLY_DELAY' => $request->auto_reply_delay,
            'WHATSAPP_MAX_DAILY_MESSAGES' => $request->max_daily_messages,
            'WHATSAPP_NOTIFICATION_EMAIL' => $request->notification_email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'WhatsApp settings updated successfully'
        ]);
    }
}
