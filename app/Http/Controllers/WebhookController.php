<?php

namespace App\Http\Controllers;

use App\Services\WorkflowEngine;
use App\Services\WhatsAppService;
use App\Models\WorkflowConversationHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class WebhookController extends Controller
{
    private WorkflowEngine $workflowEngine;
    private WhatsAppService $whatsappService;

    public function __construct(WorkflowEngine $workflowEngine, WhatsAppService $whatsappService)
    {
        $this->workflowEngine = $workflowEngine;
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle WhatsApp webhook verification
     */
    public function verify(Request $request): string
    {
        try {
            // Meta sends parameters with dots, not underscores
            $mode = $request->query('hub.mode') ?: $request->query('hub_mode');
            $token = $request->query('hub.verify_token') ?: $request->query('hub_verify_token');
            $challenge = $request->query('hub.challenge') ?: $request->query('hub_challenge');

            // Handle verification challenge directly if parameters are provided
            if ($mode && $token && $challenge) {
                return $this->whatsappService->verifyWebhook($mode, $token, $challenge);
            }

            // If this is a GET request without proper verification parameters, 
            // check if it's a direct verification challenge
            if ($request->isMethod('GET')) {
                $hubChallenge = $request->get('hub.challenge');
                $hubMode = $request->get('hub.mode');
                $hubVerifyToken = $request->get('hub.verify_token');
                
                if ($hubMode === 'subscribe' && $hubVerifyToken === config('services.whatsapp.verify_token')) {
                    return $hubChallenge;
                }
            }

            return $this->whatsappService->verifyWebhook($mode ?? '', $token ?? '', $challenge ?? '');
            
        } catch (Exception $e) {
            Log::error('Webhook verification failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            abort(403, 'Verification failed');
        }
    }

    /**
     * Handle incoming WhatsApp webhook
     */
    public function handle(Request $request)
    {
        try {
            // Handle GET request for webhook verification
            if ($request->isMethod('GET')) {
                $hubChallenge = $request->get('hub.challenge');
                $hubMode = $request->get('hub.mode');
                $hubVerifyToken = $request->get('hub.verify_token');
                
                Log::info('Webhook verification attempt', [
                    'mode' => $hubMode,
                    'token' => $hubVerifyToken,
                    'challenge' => $hubChallenge
                ]);
                
                if ($hubMode === 'subscribe' && $hubVerifyToken === config('services.whatsapp.verify_token', 'FreeDoctor2025SecureToken')) {
                    Log::info('Webhook verification successful');
                    return response($hubChallenge, 200)->header('Content-Type', 'text/plain');
                } else {
                    Log::warning('Webhook verification failed', [
                        'expected_token' => config('services.whatsapp.verify_token', 'FreeDoctor2025SecureToken'),
                        'received_token' => $hubVerifyToken
                    ]);
                    return response('Invalid verification token', 403);
                }
            }

            // Handle POST request for webhook messages
            $data = $request->json()->all();
            
            Log::info('WhatsApp webhook received', ['data' => $data]);

            // Process webhook data
            if (isset($data['entry'])) {
                foreach ($data['entry'] as $entry) {
                    if (isset($entry['changes'])) {
                        foreach ($entry['changes'] as $change) {
                            $this->processWebhookChange($change);
                        }
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Process individual webhook change
     */
    private function processWebhookChange(array $change): void
    {
        $field = $change['field'] ?? null;
        $value = $change['value'] ?? [];

        if ($field !== 'messages') {
            return;
        }

        // Process incoming messages
        if (isset($value['messages'])) {
            foreach ($value['messages'] as $message) {
                $this->processIncomingMessage($message, $value['contacts'] ?? []);
            }
        }

        // Process message statuses (delivered, read, etc.)
        if (isset($value['statuses'])) {
            foreach ($value['statuses'] as $status) {
                $this->processMessageStatus($status);
            }
        }
    }

    /**
     * Process incoming message
     */
    private function processIncomingMessage(array $message, array $contacts): void
    {
        try {
            $messageId = $message['id'];
            $from = $message['from'];
            $timestamp = $message['timestamp'];
            $type = $message['type'];

            // Get contact info
            $contact = collect($contacts)->firstWhere('wa_id', $from);
            $contactName = $contact['profile']['name'] ?? null;

            // Mark message as read
            $this->whatsappService->markAsRead($messageId);

            // Extract message content based on type
            $messageContent = $this->extractMessageContent($message, $type);
            
            if (!$messageContent) {
                Log::warning('Could not extract message content', ['message' => $message]);
                return;
            }

            Log::info('Processing incoming message', [
                'from' => $from,
                'type' => $type,
                'content' => substr($messageContent, 0, 100) . '...'
            ]);

            // Find existing user (you might want to implement user lookup)
            $userId = $this->findOrCreateUser($from, $contactName);

            // Process message through workflow engine
            $result = $this->workflowEngine->processMessage(
                $from,
                $messageContent,
                $userId,
                [
                    'message_id' => $messageId,
                    'message_type' => $type,
                    'contact_name' => $contactName,
                    'timestamp' => $timestamp
                ]
            );

            Log::info('Workflow processing result', [
                'from' => $from,
                'success' => $result['success'],
                'workflow_id' => $result['workflow_id'] ?? null
            ]);

        } catch (Exception $e) {
            Log::error('Failed to process incoming message', [
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Send error response to user
            try {
                $this->whatsappService->sendMessage(
                    $message['from'],
                    "😔 Sorry, I'm having trouble processing your message right now. Please try again in a few minutes."
                );
            } catch (Exception $sendError) {
                Log::error('Failed to send error message', [
                    'send_error' => $sendError->getMessage()
                ]);
            }
        }
    }

    /**
     * Process message delivery status
     */
    private function processMessageStatus(array $status): void
    {
        try {
            $messageId = $status['id'];
            $recipientId = $status['recipient_id'];
            $statusType = $status['status'];
            $timestamp = $status['timestamp'];

            Log::info('Message status update', [
                'message_id' => $messageId,
                'recipient' => $recipientId,
                'status' => $statusType,
                'timestamp' => $timestamp
            ]);

            // Update conversation history
            $conversation = WorkflowConversationHistory::where('message_id', $messageId)
                ->where('message_type', 'outgoing')
                ->first();

            if ($conversation) {
                switch ($statusType) {
                    case 'delivered':
                        $conversation->markAsDelivered();
                        break;
                    case 'read':
                        $conversation->markAsRead();
                        break;
                    case 'failed':
                        $conversation->update(['delivery_status' => 'failed']);
                        break;
                }
            }

        } catch (Exception $e) {
            Log::error('Failed to process message status', [
                'status' => $status,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Extract message content based on type
     */
    private function extractMessageContent(array $message, string $type): ?string
    {
        switch ($type) {
            case 'text':
                return $message['text']['body'] ?? null;
            
            case 'button':
                return $message['button']['text'] ?? null;
            
            case 'interactive':
                if (isset($message['interactive']['button_reply'])) {
                    return $message['interactive']['button_reply']['title'] ?? null;
                }
                if (isset($message['interactive']['list_reply'])) {
                    return $message['interactive']['list_reply']['title'] ?? null;
                }
                return null;
            
            case 'image':
            case 'document':
            case 'audio':
            case 'video':
                // For media messages, we might want to download and process them
                // For now, just return a placeholder
                return "[{$type} received]";
            
            case 'location':
                $latitude = $message['location']['latitude'] ?? 0;
                $longitude = $message['location']['longitude'] ?? 0;
                return "Location: {$latitude}, {$longitude}";
            
            case 'contacts':
                $contactName = $message['contacts'][0]['name']['formatted_name'] ?? 'Contact';
                return "Contact shared: {$contactName}";
            
            default:
                Log::warning('Unknown message type', ['type' => $type, 'message' => $message]);
                return "[Unknown message type: {$type}]";
        }
    }

    /**
     * Find or create user based on WhatsApp number
     */
    private function findOrCreateUser(string $whatsappNumber, ?string $contactName): ?int
    {
        try {
            // Try to find existing user by phone number
            $user = \App\Models\User::where('phone', $whatsappNumber)
                ->orWhere('phone', '+' . $whatsappNumber)
                ->first();

            if ($user) {
                return $user->id;
            }

            // Create new user if not found
            $user = \App\Models\User::create([
                'name' => $contactName ?: 'WhatsApp User',
                'phone' => $whatsappNumber,
                'email' => null, // Will be filled later if provided
                'password' => bcrypt(\Illuminate\Support\Str::random(20)), // Random password
                'user_type' => 'patient' // Default to patient
            ]);

            Log::info('Created new user from WhatsApp', [
                'user_id' => $user->id,
                'phone' => $whatsappNumber,
                'name' => $contactName
            ]);

            return $user->id;

        } catch (Exception $e) {
            Log::error('Failed to find or create user', [
                'whatsapp_number' => $whatsappNumber,
                'contact_name' => $contactName,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Test webhook endpoint
     */
    public function test(Request $request): JsonResponse
    {
        try {
            // Simulate processing a test message
            $result = $this->workflowEngine->processMessage(
                '1234567890',
                'Hello, I need help finding a doctor',
                null,
                ['test' => true]
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook test completed',
                'result' => $result
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
