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
        $data = $request->all();
        
        // Log incoming webhook data for debugging
        Log::info('WhatsApp Webhook Data:', $data);
        
        // Debug output for console testing
        if (app()->runningInConsole()) {
            echo "DEBUG: Webhook called with data: " . json_encode($data) . "\n";
        }
        
        // WhatsApp webhook verification
        if ($request->isMethod('get')) {
            $verify_token = config('services.whatsapp.verify_token', 'freedoctor_webhook_token');
            
            // Meta sends parameters with dots, check both formats
            $mode = $request->input('hub.mode') ?: $request->input('hub_mode');
            $token = $request->input('hub.verify_token') ?: $request->input('hub_verify_token');
            $challenge = $request->input('hub.challenge') ?: $request->input('hub_challenge');
            
            Log::info('WhatsApp Webhook Verification:', [
                'mode' => $mode,
                'token' => $token,
                'challenge' => $challenge
            ]);
            
            if ($mode === 'subscribe' && $token === $verify_token) {
                return response($challenge, 200);
            }
            return response('Invalid verification', 403);
        }

        // Handle incoming message
        $entry = $data['entry'][0] ?? null;
        $changes = $entry['changes'][0] ?? null;
        $messages = $changes['value']['messages'][0] ?? null;
        
        if (app()->runningInConsole()) {
            echo "DEBUG: entry=" . ($entry ? "exists" : "null") . ", changes=" . ($changes ? "exists" : "null") . ", messages=" . ($messages ? "exists" : "null") . "\n";
        }
        
        Log::info('WhatsApp Message Processing:', [
            'entry_exists' => $entry !== null,
            'changes_exists' => $changes !== null,
            'messages_exists' => $messages !== null,
            'messages_data' => $messages
        ]);
        
        if ($messages) {
            $from = $messages['from'];
            $messageId = $messages['id'] ?? null;
            $text = $messages['text']['body'] ?? '';
            $timestamp = $messages['timestamp'] ?? time();
            
            if (app()->runningInConsole()) {
                echo "DEBUG: Processing message from $from: '$text'\n";
            }
            
            Log::info('Processing WhatsApp message:', [
                'from' => $from,
                'text' => $text,
                'message_id' => $messageId,
                'timestamp' => $timestamp
            ]);
            
            // Save incoming message with real-time update capability
            try {
                $conversation = WhatsappConversation::create([
                    'phone' => $from,
                    'message' => $text,
                    'message_id' => $messageId,
                    'sent_at' => now(),
                    'is_responded' => false,
                    'lead_status' => 'new',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                if (app()->runningInConsole()) {
                    echo "DEBUG: Message saved with ID: " . $conversation->id . "\n";
                }
                
                Log::info('New conversation saved for real-time updates:', [
                    'id' => $conversation->id, 
                    'phone' => $conversation->phone,
                    'message_preview' => substr($text, 0, 50)
                ]);
                
                // Find auto reply
                $autoReply = WhatsappAutoReply::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(keyword), "%")', [$text])->first();
                
                if (app()->runningInConsole()) {
                    echo "DEBUG: Auto-reply found: " . ($autoReply ? $autoReply->keyword : "none") . "\n";
                }
                
                Log::info('Auto-reply search:', [
                    'text' => $text,
                    'auto_reply_found' => $autoReply !== null,
                    'auto_reply_keyword' => $autoReply?->keyword ?? 'none'
                ]);
                
                if ($autoReply) {
                    $reply = $autoReply->reply_type === 'gpt' 
                        ? $this->generateGptReply($autoReply->gpt_prompt, $text) 
                        : $autoReply->reply_content;
                        
                    Log::info('Generated reply:', [
                        'reply_type' => $autoReply->reply_type,
                        'reply_content' => substr($reply, 0, 100)
                    ]);
                        
                    $service = new WhatsappCloudApiService();
                    $result = $service->sendMessage($from, $reply);
                    
                    Log::info('WhatsApp API send result:', ['result' => $result]);
                    
                    // Update the conversation with reply
                    $conversation->update([
                        'reply' => $reply,
                        'reply_type' => $autoReply->reply_type,
                        'is_responded' => true,
                        'updated_at' => now()
                    ]);
                    
                    if (app()->runningInConsole()) {
                        echo "DEBUG: Reply sent and conversation updated\n";
                    }
                    
                    Log::info('Auto-reply sent for real-time display:', [
                        'conversation_id' => $conversation->id,
                        'reply_preview' => substr($reply, 0, 50),
                        'type' => $autoReply->reply_type
                    ]);
                } else {
                    Log::info('No auto-reply found for message: ' . $text);
                }
                
            } catch (\Exception $e) {
                if (app()->runningInConsole()) {
                    echo "DEBUG: Error saving message: " . $e->getMessage() . "\n";
                }
                
                Log::error('Error saving WhatsApp message:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } else {
            if (app()->runningInConsole()) {
                echo "DEBUG: No messages found in webhook data\n";
            }
            Log::info('No messages found in webhook data');
        }
        
        return response('EVENT_RECEIVED', 200);
    }

    protected function generateGptReply($prompt, $userMessage)
    {
        // TODO: Integrate with OpenAI API or your GPT provider
        return '[GPT reply based on prompt: ' . $prompt . ' and user message: ' . $userMessage . ']';
    }
}
