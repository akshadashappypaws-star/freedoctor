<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Models\WhatsappConversation;
use App\Models\WorkflowConversationHistory;
use App\Models\Workflow;
use App\Services\WhatsAppService;

class WhatsappConversationController extends Controller
{
    private WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }
    public function index(Request $request)
    {
        // Check if this is an AJAX request for real-time updates
        if ($request->ajax() || $request->has('ajax')) {
            return $this->getAjaxUpdate($request);
        }

        // Get all conversations and group them by phone
        $allConversations = WhatsappConversation::orderBy('created_at', 'desc')->get();
        $groupedConversations = $allConversations->groupBy('phone');
        
        // Create conversation summary data
        $conversationSummaries = [];
        foreach ($groupedConversations as $phone => $phoneConversations) {
            $latest = $phoneConversations->first();
            $messageCount = $phoneConversations->count();
            
            // Get the last actual message (either incoming or outgoing)
            $lastMessage = $phoneConversations->where('message', '!=', null)->first()?->message 
                        ?? $phoneConversations->where('reply', '!=', null)->first()?->reply 
                        ?? 'No messages';
            
            $conversationSummaries[] = [
                'phone' => $phone,
                'last_interaction' => $latest->updated_at ?? $latest->created_at ?? now(),
                'message_count' => $messageCount,
                'status' => $latest->lead_status ?? 'active',
                'last_message' => $lastMessage,
                'created_at' => $latest->created_at ?? now(),
                'updated_at' => $latest->updated_at ?? now()
            ];
        }
        
        // Sort by last interaction and paginate manually
        $conversationSummaries = collect($conversationSummaries)
            ->sortByDesc('last_interaction')
            ->values();
        
        // Manual pagination
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedItems = $conversationSummaries->slice($offset, $perPage);
        
        // Convert arrays to objects for easier template access
        $paginatedObjects = $paginatedItems->map(function($item) {
            return (object)$item;
        });
        
        $conversations = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedObjects,
            $conversationSummaries->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Process conversations to add user info and format data
        $conversationDetails = [];
        foreach ($conversations as $conversation) {
            $userInfo = $this->getUserInfo($conversation->phone);
            
            $conversationDetails[$conversation->phone] = [
                'phone' => $conversation->phone,
                'user_name' => $userInfo['name'] ?? 'Unknown User',
                'last_message' => $conversation->last_message,
                'last_interaction' => $conversation->last_interaction,
                'status' => $conversation->status,
                'message_count' => $conversation->message_count,
                'user_type' => $userInfo['type'] ?? 'guest'
            ];
        }
        
        // Calculate statistics
        $statistics = [
            'total_conversations' => $groupedConversations->count(),
            'active_conversations' => $groupedConversations->filter(function($phoneConversations) {
                return $phoneConversations->first()->lead_status === 'active';
            })->count(),
            'messages_today' => WhatsappConversation::whereDate('created_at', today())->count(),
            'active_workflows' => Workflow::where('status', 'running')->count(),
        ];
        
        return view('admin.pages.whatsapp.conversations', compact(
            'conversations', 
            'conversationDetails', 
            'statistics'
        ));
    }

    /**
     * Get real-time updates via AJAX
     */
    private function getAjaxUpdate(Request $request)
    {
        $since = $request->get('since');
        $sinceTime = $since ? \Carbon\Carbon::parse($since) : now()->subMinutes(5);
        
        // Get recent conversations
        $recentConversations = WhatsappConversation::where('updated_at', '>', $sinceTime)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('phone');
        
        $updatedConversations = [];
        $newMessageCount = 0;
        
        foreach ($recentConversations as $phone => $phoneConversations) {
            $latest = $phoneConversations->first();
            $messageCount = WhatsappConversation::where('phone', $phone)->count();
            
            // Count new messages since last check
            $newMessages = WhatsappConversation::where('phone', $phone)
                ->where('created_at', '>', $sinceTime)
                ->count();
            $newMessageCount += $newMessages;
            
            $lastMessage = $phoneConversations->where('message', '!=', null)->first()?->message 
                        ?? $phoneConversations->where('reply', '!=', null)->first()?->reply 
                        ?? 'No messages';
            
            $userInfo = $this->getUserInfo($phone);
            
            $updatedConversations[] = [
                'phone' => $phone,
                'details' => [
                    'phone' => $phone,
                    'user_name' => $userInfo['name'] ?? 'Unknown User',
                    'last_message' => $lastMessage,
                    'last_interaction' => $latest->updated_at ?? $latest->created_at,
                    'status' => $latest->lead_status ?? 'active',
                    'message_count' => $messageCount,
                    'user_type' => $userInfo['type'] ?? 'guest'
                ]
            ];
        }
        
        // Calculate updated statistics
        $statistics = [
            'total_conversations' => WhatsappConversation::select('phone')->distinct()->count(),
            'active_conversations' => WhatsappConversation::where('lead_status', 'active')
                ->select('phone')->distinct()->count(),
            'messages_today' => WhatsappConversation::whereDate('created_at', today())->count(),
            'active_workflows' => Workflow::where('status', 'running')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'conversations' => $updatedConversations,
            'statistics' => $statistics,
            'newMessageCount' => $newMessageCount,
            'timestamp' => now()->toISOString()
        ]);
    }


    /**
     * Show specific conversation details
     */
    public function show(Request $request, string $phone): View|\Illuminate\Http\JsonResponse
    {
        // Decode the phone number if it's URL encoded
        $whatsappNumber = urldecode($phone);
        
        // Check if this is an AJAX request for real-time updates
        if ($request->ajax() || $request->has('ajax')) {
            return $this->getMessageUpdates($request, $whatsappNumber);
        }
        
        // Try to find conversations with both formats (with and without +)
        $messages = WhatsappConversation::where(function($query) use ($whatsappNumber) {
            $query->where('phone', $whatsappNumber);
            
            // Also search for variations of the phone number
            if (strpos($whatsappNumber, '+') === 0) {
                // If has +, also search without +
                $query->orWhere('phone', substr($whatsappNumber, 1));
            } else {
                // If no +, also search with +
                $query->orWhere('phone', '+' . $whatsappNumber);
            }
        })->orderBy('created_at', 'asc')->paginate(50);

        // Get active workflow if exists
        $activeWorkflow = Workflow::where('whatsapp_number', $whatsappNumber)
            ->where('status', 'running')
            ->first();

        // Get conversation statistics (handle phone number variations)
        $phoneQuery = function($query) use ($whatsappNumber) {
            $query->where('phone', $whatsappNumber);
            if (strpos($whatsappNumber, '+') === 0) {
                $query->orWhere('phone', substr($whatsappNumber, 1));
            } else {
                $query->orWhere('phone', '+' . $whatsappNumber);
            }
        };
        
        $stats = [
            'total_messages' => WhatsappConversation::where($phoneQuery)->count(),
            'replied_messages' => WhatsappConversation::where($phoneQuery)
                ->where('is_responded', 1)->count(),
            'pending_messages' => WhatsappConversation::where($phoneQuery)
                ->where('is_responded', 0)->count(),
            'lead_status' => WhatsappConversation::where($phoneQuery)
                ->orderBy('created_at', 'desc')->first()?->lead_status ?? 'new',
            'first_message' => WhatsappConversation::where($phoneQuery)
                ->orderBy('created_at', 'asc')->first()?->created_at,
            'last_message' => WhatsappConversation::where($phoneQuery)
                ->orderBy('created_at', 'desc')->first()?->created_at,
        ];

        // Get user information
        $userInfo = $this->getUserInfo($whatsappNumber);

        return view('admin.pages.whatsapp.conversation-details', compact(
            'whatsappNumber',
            'messages',
            'activeWorkflow',
            'stats',
            'userInfo'
        ));
    }

    /**
     * Get new messages for real-time updates
     */
    private function getMessageUpdates(Request $request, string $whatsappNumber)
    {
        $since = $request->get('since');
        $sinceTime = $since ? \Carbon\Carbon::parse($since) : now()->subMinutes(5);
        
        // Get new messages since last check
        $newMessages = WhatsappConversation::where('phone', $whatsappNumber)
            ->where('created_at', '>', $sinceTime)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Get updated statistics
        $stats = [
            'total_messages' => WhatsappConversation::where('phone', $whatsappNumber)->count(),
            'replied_messages' => WhatsappConversation::where('phone', $whatsappNumber)
                ->where('is_responded', 1)->count(),
            'pending_messages' => WhatsappConversation::where('phone', $whatsappNumber)
                ->where('is_responded', 0)->count(),
            'lead_status' => WhatsappConversation::where('phone', $whatsappNumber)
                ->orderBy('created_at', 'desc')->first()?->lead_status ?? 'new',
        ];
        
        return response()->json([
            'success' => true,
            'newMessages' => $newMessages,
            'stats' => $stats,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Admin intervention in conversation
     */
    public function intervene(Request $request, string $phone): JsonResponse
    {
        $whatsappNumber = urldecode($phone);
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'action' => 'required|in:send_message,pause_bot,resume_bot,transfer_human'
        ]);

        try {
            switch ($validated['action']) {
                case 'send_message':
                    // Send admin message
                    $result = $this->whatsappService->sendMessage(
                        $whatsappNumber,
                        "🔹 *Admin Message:*\n\n" . $validated['message']
                    );

                    // Log the intervention in WhatsappConversation
                    WhatsappConversation::create([
                        'phone' => $whatsappNumber,
                        'message' => '[Admin] Manual message sent',
                        'reply' => $validated['message'],
                        'reply_type' => 'admin',
                        'sent_at' => now(),
                        'is_responded' => true,
                        'lead_status' => 'active'
                    ]);

                    break;

                case 'pause_bot':
                    // Pause active workflow
                    $workflow = Workflow::where('whatsapp_number', $whatsappNumber)
                        ->where('status', 'running')
                        ->first();

                    if ($workflow) {
                        $workflow->update(['status' => 'paused']);
                    }

                    $this->whatsappService->sendMessage(
                        $whatsappNumber,
                        "⏸️ Bot has been paused. An admin will assist you shortly."
                    );

                    break;

                case 'resume_bot':
                    // Resume paused workflow
                    $workflow = Workflow::where('whatsapp_number', $whatsappNumber)
                        ->where('status', 'paused')
                        ->first();

                    if ($workflow) {
                        $workflow->update(['status' => 'running']);
                    }

                    $this->whatsappService->sendMessage(
                        $whatsappNumber,
                        "▶️ Bot assistance has been resumed."
                    );

                    break;

                case 'transfer_human':
                    // Transfer to human support
                    $workflow = Workflow::where('whatsapp_number', $whatsappNumber)
                        ->where('status', 'running')
                        ->first();

                    if ($workflow) {
                        $workflow->update(['status' => 'transferred']);
                    }

                    $this->whatsappService->sendMessage(
                        $whatsappNumber,
                        "👥 You've been connected to our human support team. They will assist you shortly."
                    );

                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Intervention completed successfully',
                'action' => $validated['action']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Intervention failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handover conversation to human agent
     */
    public function handover(Request $request, string $phone): JsonResponse
    {
        $whatsappNumber = urldecode($phone);
        $validated = $request->validate([
            'agent_id' => 'sometimes|exists:admins,id',
            'notes' => 'sometimes|string|max:500'
        ]);

        try {
            // Pause any active workflow
            $workflow = Workflow::where('whatsapp_number', $whatsappNumber)
                ->where('status', 'running')
                ->first();

            if ($workflow) {
                $workflow->update([
                    'status' => 'handed_over',
                    'notes' => $validated['notes'] ?? 'Handed over to human agent'
                ]);
            }

            // Send handover message
            $message = "👥 *Handover to Human Support*\n\n";
            $message .= "Your conversation has been transferred to our human support team. ";
            $message .= "A representative will assist you shortly.\n\n";
            
            if (isset($validated['notes'])) {
                $message .= "📝 *Notes:* " . $validated['notes'];
            }

            $this->whatsappService->sendMessage($whatsappNumber, $message);

            // Log the handover in WhatsappConversation
            WhatsappConversation::create([
                'phone' => $whatsappNumber,
                'message' => '[System] Conversation handed over to human support',
                'reply' => $message,
                'reply_type' => 'handover',
                'sent_at' => now(),
                'is_responded' => true,
                'lead_status' => 'transferred'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Conversation handed over to human agent successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Handover failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user information from phone number
     */
    private function getUserInfo(string $whatsappNumber): array
    {
        // Clean phone number for better matching
        $cleanPhone = preg_replace('/[^0-9]/', '', $whatsappNumber);
        $last10Digits = substr($cleanPhone, -10);
        
        // Try to find user by phone number
        $user = \App\Models\User::where(function($query) use ($whatsappNumber, $cleanPhone, $last10Digits) {
            $query->where('phone', $whatsappNumber)
                  ->orWhere('phone', $cleanPhone)
                  ->orWhere('phone', 'like', '%' . $last10Digits);
        })->first();

        if ($user) {
            return [
                'id' => $user->id,
                'name' => $user->username ?? $user->name ?? $user->email,
                'email' => $user->email,
                'registration_date' => $user->created_at,
                'type' => 'registered'
            ];
        }

        // If no registered user found, check if we have conversation history
        $firstConversation = WhatsappConversation::where('phone', $whatsappNumber)
            ->orderBy('created_at', 'asc')
            ->first();

        return [
            'phone' => $whatsappNumber,
            'name' => 'Guest User (' . substr($whatsappNumber, -4) . ')',
            'type' => 'guest',
            'first_contact' => $firstConversation?->created_at
        ];
    }

    /**
     * Update conversation status
     */
    public function updateStatus(Request $request, string $whatsappNumber): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:active,paused,closed,transferred'
        ]);

        try {
            $workflow = Workflow::where('whatsapp_number', $whatsappNumber)->first();
            
            if ($workflow) {
                $workflow->update(['status' => $validated['status']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send message to conversation
     */
    public function sendMessage(Request $request, string $whatsappNumber): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            $this->whatsappService->sendMessage($whatsappNumber, $validated['message']);

            // Log the message in WhatsappConversation
            WhatsappConversation::create([
                'phone' => $whatsappNumber,
                'message' => '[Admin] Direct message sent',
                'reply' => $validated['message'],
                'reply_type' => 'admin',
                'sent_at' => now(),
                'is_responded' => true,
                'lead_status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get conversation analytics
     */
    public function analytics(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $startDate = now()->subDays($days);

            $analytics = [
                'total_conversations' => WhatsappConversation::distinct('phone')->count(),
                'conversations_period' => WhatsappConversation::where('created_at', '>=', $startDate)
                    ->distinct('phone')->count(),
                'messages_period' => WhatsappConversation::where('created_at', '>=', $startDate)->count(),
                'active_workflows' => Workflow::where('status', 'running')->count(),
                'daily_stats' => WhatsappConversation::where('created_at', '>=', $startDate)
                    ->selectRaw('DATE(created_at) as date, COUNT(*) as messages')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
            ];

            return response()->json($analytics);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get analytics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update conversations
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'conversation_ids' => 'required|array',
            'action' => 'required|in:close,pause,resume,transfer'
        ]);

        try {
            $updated = 0;
            foreach ($validated['conversation_ids'] as $whatsappNumber) {
                $workflow = Workflow::where('whatsapp_number', $whatsappNumber)->first();
                if ($workflow) {
                    $status = match($validated['action']) {
                        'close' => 'completed',
                        'pause' => 'paused',
                        'resume' => 'running',
                        'transfer' => 'transferred'
                    };
                    
                    $workflow->update(['status' => $status]);
                    $updated++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Updated $updated conversations"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export conversations
     */
    public function export(Request $request)
    {
        try {
            $conversations = WhatsappConversation::orderBy('created_at', 'desc')->get();

            $csvData = [];
            $csvData[] = ['Phone', 'Message', 'Reply', 'Reply Type', 'Sent At', 'Lead Status'];

            foreach ($conversations as $conversation) {
                $csvData[] = [
                    $conversation->phone,
                    $conversation->message ?? '',
                    $conversation->reply ?? '',
                    $conversation->reply_type ?? '',
                    $conversation->sent_at ?? $conversation->created_at,
                    $conversation->lead_status ?? 'active'
                ];
            }

            $filename = 'whatsapp_conversations_' . now()->format('Y-m-d_H-i-s') . '.csv';
            
            $handle = fopen('php://temp', 'w+');
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"$filename\"");

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }
}