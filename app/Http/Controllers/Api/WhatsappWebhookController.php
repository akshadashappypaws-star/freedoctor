<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappAutoReply;
use App\Models\WhatsappConversation;
use App\Services\WhatsappCloudApiService;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Always log the raw request data first with v23.0 context
        $rawData = $request->all();
        $httpMethod = $request->method();
        $userAgent = $request->header('User-Agent');
        $ipAddress = $request->ip();
        
        // Enhanced logging for debugging v23.0 API
        Log::info('🔔 WhatsApp v23.0 Webhook Request Received', [
            'api_version' => 'v23.0',
            'method' => $httpMethod,
            'ip' => $ipAddress,
            'user_agent' => $userAgent,
            'url' => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'raw_data' => $rawData,
            'content_type' => $request->header('Content-Type'),
            'timestamp' => now()->toISOString(),
            'phone_number_test' => '+918519931876' // Your test number
        ]);
        
        $data = $rawData;
        
        // Debug output for console testing
        if (app()->runningInConsole()) {
            echo "🔔 DEBUG: Webhook called with method: $httpMethod\n";
            echo "📊 DEBUG: Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        }
        
        // WhatsApp webhook verification (GET request)
        if ($request->isMethod('get')) {
            $verify_token = env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'FreeDoctor2025SecureToken');
            
            // Meta sends parameters with dots, check both formats
            $mode = $request->input('hub.mode') ?: $request->input('hub_mode');
            $token = $request->input('hub.verify_token') ?: $request->input('hub_verify_token');
            $challenge = $request->input('hub.challenge') ?: $request->input('hub_challenge');
            
            Log::info('🔐 WhatsApp Webhook Verification Attempt', [
                'mode' => $mode,
                'token_provided' => $token ? 'YES (' . strlen($token) . ' chars)' : 'NO',
                'token_expected' => $verify_token,
                'token_matches' => $token === $verify_token,
                'challenge' => $challenge,
                'url' => $request->fullUrl(),
                'all_params' => $request->all()
            ]);
            
            // If this is a direct visit (no verification parameters), show status page
            if (!$mode && !$token && !$challenge) {
                $statusResponse = [
                    'status' => 'WEBHOOK_ACTIVE',
                    'message' => '✅ WhatsApp Webhook Endpoint is Live!',
                    'timestamp' => now()->toISOString(),
                    'endpoint' => $request->url(),
                    'environment' => [
                        'app_env' => config('app.env'),
                        'app_url' => config('app.url'),
                        'webhook_verify_token' => $verify_token ? 'CONFIGURED' : 'NOT_SET'
                    ],
                    'test_verification_url' => $request->url() . '?hub.mode=subscribe&hub.verify_token=' . urlencode($verify_token) . '&hub.challenge=TEST123',
                    'expected_params' => [
                        'hub.mode' => 'subscribe',
                        'hub.verify_token' => 'your_verification_token',
                        'hub.challenge' => 'verification_challenge'
                    ],
                    'instructions' => [
                        '1. Copy the test_verification_url above',
                        '2. Paste it in your browser - should return TEST123',
                        '3. If that works, your webhook verification is correct',
                        '4. Check Meta Developer Console for webhook delivery logs'
                    ]
                ];
                
                Log::info('📋 Webhook Status Page Accessed', $statusResponse);
                
                return response()->json($statusResponse, 200, [], JSON_PRETTY_PRINT);
            }
            
            // Verify the webhook
            if ($mode === 'subscribe' && $token === $verify_token) {
                Log::info('✅ Webhook Verification Successful', [
                    'challenge' => $challenge,
                    'returning' => 'challenge_string'
                ]);
                
                return response($challenge, 200);
            }
            
            Log::error('❌ Webhook Verification Failed', [
                'mode_received' => $mode,
                'mode_expected' => 'subscribe',
                'token_received' => $token ? 'PROVIDED' : 'MISSING',
                'token_expected' => $verify_token,
                'token_matches' => $token === $verify_token
            ]);
            
            return response()->json([
                'error' => '❌ Webhook Verification Failed',
                'received' => [
                    'mode' => $mode,
                    'token_provided' => $token ? 'YES' : 'NO',
                    'token_length' => $token ? strlen($token) : 0,
                    'challenge' => $challenge ? 'YES' : 'NO'
                ],
                'expected' => [
                    'mode' => 'subscribe',
                    'verify_token' => $verify_token,
                    'challenge' => 'any_string'
                ],
                'debug_info' => [
                    'token_matches' => $token === $verify_token,
                    'configured_token' => $verify_token ? 'SET' : 'NOT_SET'
                ]
            ], 403, [], JSON_PRETTY_PRINT);
        }

        // Handle incoming messages (POST request)
        if ($request->isMethod('post')) {
            Log::info('📨 Processing POST webhook data', [
                'data_structure' => [
                    'has_entry' => isset($data['entry']),
                    'entry_count' => isset($data['entry']) ? count($data['entry']) : 0,
                    'raw_size_bytes' => strlen(json_encode($data))
                ]
            ]);
            
            // Enhanced message extraction
            $entry = $data['entry'][0] ?? null;
            $changes = $entry['changes'][0] ?? null;
            $value = $changes['value'] ?? null;
            $messages = $value['messages'] ?? [];
            $contacts = $value['contacts'] ?? [];
            
            if (app()->runningInConsole()) {
                echo "📨 DEBUG: POST request processing\n";
                echo "📊 DEBUG: entry=" . ($entry ? "exists" : "null") . "\n";
                echo "📊 DEBUG: changes=" . ($changes ? "exists" : "null") . "\n";
                echo "📊 DEBUG: messages count=" . count($messages) . "\n";
            }
            
            Log::info('📊 WhatsApp Message Structure Analysis', [
                'entry_exists' => $entry !== null,
                'changes_exists' => $changes !== null,
                'value_exists' => $value !== null,
                'messages_count' => count($messages),
                'contacts_count' => count($contacts),
                'webhook_data_structure' => [
                    'entry' => $entry ? 'EXISTS' : 'MISSING',
                    'changes' => $changes ? 'EXISTS' : 'MISSING',
                    'value' => $value ? 'EXISTS' : 'MISSING',
                    'messages' => !empty($messages) ? 'EXISTS (' . count($messages) . ')' : 'EMPTY',
                    'contacts' => !empty($contacts) ? 'EXISTS (' . count($contacts) . ')' : 'EMPTY'
                ]
            ]);
            
            // Process each message
            foreach ($messages as $message) {
                $this->processIncomingMessage($message, $contacts);
            }
            
            // If no messages but webhook received, log for debugging
            if (empty($messages)) {
                Log::warning('⚠️ Webhook received but no messages found', [
                    'possible_causes' => [
                        'webhook_event_type' => $changes['field'] ?? 'unknown',
                        'value_data' => $value,
                        'might_be_status_update' => isset($value['statuses']),
                        'might_be_system_message' => isset($value['system'])
                    ]
                ]);
            }
        }
        
        // Always return success response
        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Process individual incoming WhatsApp message (v23.0 enhanced)
     */
    protected function processIncomingMessage($message, $contacts = [])
    {
        $from = $message['from'];
        $messageId = $message['id'] ?? null;
        $timestamp = $message['timestamp'] ?? time();
        $messageType = $message['type'] ?? 'unknown';
        
        // Handle Indian phone number format (+918519931876)
        $formattedPhone = $from;
        if (!str_starts_with($from, '+') && str_starts_with($from, '91')) {
            $formattedPhone = '+' . $from;
        } elseif (!str_starts_with($from, '+') && !str_starts_with($from, '91')) {
            $formattedPhone = '+91' . $from;
        }
        
        // Extract message content based on type (v23.0 structure)
        $messageText = '';
        $messageData = [];
        
        switch ($messageType) {
            case 'text':
                $messageText = $message['text']['body'] ?? '';
                break;
            case 'image':
                $messageText = '[Image]' . ($message['image']['caption'] ?? '');
                $messageData = $message['image'];
                break;
            case 'document':
                $messageText = '[Document] ' . ($message['document']['filename'] ?? 'Unknown file');
                $messageData = $message['document'];
                break;
            case 'location':
                $lat = $message['location']['latitude'] ?? 0;
                $lng = $message['location']['longitude'] ?? 0;
                $messageText = "[Location] Lat: $lat, Lng: $lng";
                $messageData = $message['location'];
                break;
            case 'audio':
                $messageText = '[Audio message]';
                $messageData = $message['audio'];
                break;
            case 'video':
                $messageText = '[Video]' . ($message['video']['caption'] ?? '');
                $messageData = $message['video'];
                break;
            case 'sticker':
                $messageText = '[Sticker]';
                $messageData = $message['sticker'] ?? [];
                break;
            case 'reaction':
                $emoji = $message['reaction']['emoji'] ?? '👍';
                $messageText = "[Reaction] $emoji";
                $messageData = $message['reaction'] ?? [];
                break;
            default:
                $messageText = "[{$messageType} message]";
                $messageData = $message[$messageType] ?? [];
        }
        
        // Get contact info if available (v23.0 format)
        $contactName = '';
        foreach ($contacts as $contact) {
            if (($contact['wa_id'] ?? '') === $from || ($contact['wa_id'] ?? '') === ltrim($from, '+')) {
                $contactName = $contact['profile']['name'] ?? '';
                break;
            }
        }
        
        if (app()->runningInConsole()) {
            echo "📨 DEBUG v23.0: Processing {$messageType} message from $from" . ($contactName ? " ($contactName)" : '') . ": '$messageText'\n";
        }
        
        Log::info('💬 Processing WhatsApp v23.0 message', [
            'api_version' => 'v23.0',
            'from' => $from,
            'formatted_phone' => $formattedPhone,
            'contact_name' => $contactName,
            'message_type' => $messageType,
            'message_text' => $messageText,
            'message_id' => $messageId,
            'timestamp' => $timestamp,
            'message_data' => $messageData,
            'is_test_number' => in_array($from, ['918519931876', '+918519931876'])
        ]);
        
        // Save incoming message with enhanced data (v23.0 format)
        try {
            $conversation = WhatsappConversation::create([
                'phone_number' => $formattedPhone, // Use formatted phone with +
                'phone' => $from, // Keep original for compatibility
                'incoming_message' => $messageText,
                'message' => $messageText, // Keep old field for compatibility
                'message_id' => $messageId,
                'message_type' => $messageType,
                'message_data' => json_encode($messageData),
                'contact_name' => $contactName,
                'webhook_data' => json_encode($message),
                'direction' => 'incoming',
                'status' => 'received',
                'received_at' => now(),
                'sent_at' => now(),
                'is_responded' => false,
                'lead_status' => 'new',
                'api_version' => 'v23.0', // Track API version
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            if (app()->runningInConsole()) {
                echo "✅ DEBUG: Message saved with ID: " . $conversation->id . "\n";
            }
            
            Log::info('✅ New WhatsApp conversation saved', [
                'id' => $conversation->id, 
                'phone' => $conversation->phone_number ?? $conversation->phone,
                'message_preview' => substr($messageText, 0, 50),
                'type' => $messageType
            ]);
            
            // Only process auto-replies for text messages
            if ($messageType === 'text' && !empty($messageText)) {
                $this->processAutoReply($conversation, $messageText, $from);
            } else {
                Log::info('⏭️ Skipping auto-reply (non-text message)', [
                    'type' => $messageType,
                    'phone' => $from
                ]);
            }
            
        } catch (\Exception $e) {
            if (app()->runningInConsole()) {
                echo "❌ DEBUG: Error saving message: " . $e->getMessage() . "\n";
            }
            
            Log::error('❌ Error saving WhatsApp message', [
                'error' => $e->getMessage(),
                'from' => $from,
                'message_text' => $messageText,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Process auto-reply for text messages
     */
    protected function processAutoReply($conversation, $text, $from)
    {
        // Find auto reply
        $autoReply = WhatsappAutoReply::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(keyword), "%")', [$text])->first();
        
        if (app()->runningInConsole()) {
            echo "🤖 DEBUG: Auto-reply search for '$text': " . ($autoReply ? $autoReply->keyword : "none found") . "\n";
        }
        
        Log::info('🤖 Auto-reply search', [
            'text' => $text,
            'auto_reply_found' => $autoReply !== null,
            'auto_reply_keyword' => $autoReply?->keyword ?? 'none',
            'phone' => $from
        ]);
        
        if ($autoReply) {
            $reply = $autoReply->reply_type === 'gpt' 
                ? $this->generateGptReply($autoReply->gpt_prompt, $text) 
                : $autoReply->reply_content;
                
            Log::info('📝 Generated auto-reply', [
                'reply_type' => $autoReply->reply_type,
                'reply_content' => substr($reply, 0, 100),
                'keyword_matched' => $autoReply->keyword
            ]);
                
            // Send reply via WhatsApp API
            try {
                $service = new WhatsappCloudApiService();
                $result = $service->sendMessage($from, $reply);
                
                Log::info('📤 WhatsApp API send result', [
                    'success' => $result['success'] ?? false,
                    'result' => $result,
                    'phone' => $from
                ]);
                
                // Update the conversation with reply
                $conversation->update([
                    'reply' => $reply,
                    'reply_type' => $autoReply->reply_type,
                    'is_responded' => true,
                    'updated_at' => now()
                ]);
                
                if (app()->runningInConsole()) {
                    echo "✅ DEBUG: Auto-reply sent and conversation updated\n";
                }
                
                Log::info('✅ Auto-reply sent successfully', [
                    'conversation_id' => $conversation->id,
                    'reply_preview' => substr($reply, 0, 50),
                    'type' => $autoReply->reply_type
                ]);
                
            } catch (\Exception $e) {
                Log::error('❌ Error sending auto-reply', [
                    'error' => $e->getMessage(),
                    'phone' => $from,
                    'reply' => $reply
                ]);
            }
            
        } else {
            Log::info('ℹ️ No auto-reply configured for message', [
                'text' => $text,
                'phone' => $from
            ]);
        }
    }

    protected function generateGptReply($prompt, $userMessage)
    {
        // TODO: Integrate with OpenAI API or your GPT provider
        return '[GPT reply based on prompt: ' . $prompt . ' and user message: ' . $userMessage . ']';
    }
}
