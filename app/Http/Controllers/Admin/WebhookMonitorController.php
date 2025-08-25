<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WebhookMonitorController extends Controller
{
    /**
     * Display webhook monitoring dashboard
     */
    public function index()
    {
        // Get environment information from .env file and config
        $envInfo = [
            'app_name' => env('APP_NAME', 'FreeDoctorCORPO'),
            'app_url' => env('APP_URL', config('app.url')),
            'app_env' => env('APP_ENV', config('app.env')),
            'app_debug' => env('APP_DEBUG', config('app.debug')),
            
            // WhatsApp Configuration
            'webhook_url' => env('WHATSAPP_WEBHOOK_URL', 'https://freedoctor.in/webhook/whatsapp'),
            'verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'FreeDoctor2025SecureToken'),
            'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID', env('WHATSAPP_CLOUD_PHONE_NUMBER_ID')),
            'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
            'api_key' => env('WHATSAPP_API_KEY'),
            'cloud_token' => env('WHATSAPP_CLOUD_TOKEN'),
            
            // Database Configuration
            'db_connection' => env('DB_CONNECTION'),
            'db_host' => env('DB_HOST'),
            'db_database' => env('DB_DATABASE'),
            'db_username' => env('DB_USERNAME'),
            
            // Other Services
            'razorpay_key' => env('RAZORPAY_KEY'),
            'google_client_id' => env('GOOGLE_CLIENT_ID'),
            'openai_api_key' => env('OPENAI_API_KEY') ? 'Configured' : 'Not Set',
            
            // Server Information
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
        ];

        // Get recent webhook statistics
        $stats = $this->getWebhookStats();

        // Get recent logs for immediate display
        $recentLogs = $this->getRecentLogs();

        // Get received messages from database
        $receivedMessages = $this->getReceivedMessages();

        return view('admin.webhook-monitor', compact('envInfo', 'stats', 'recentLogs', 'receivedMessages'));
    }

    /**
     * Get recent webhook logs via AJAX
     */
    public function getLogs(Request $request)
    {
        try {
            // Read Laravel log file
            $logFile = storage_path('logs/laravel.log');
            
            if (!file_exists($logFile)) {
                return response()->json(['logs' => [], 'message' => 'No log file found']);
            }

            // Get last 200 lines of log file for more comprehensive view
            $logs = $this->getLastLines($logFile, 200);
            
            // Filter and categorize webhook-related logs
            $webhookLogs = [];
            $errorLogs = [];
            $messageLogs = [];
            
            foreach ($logs as $log) {
                if (strpos($log, 'WhatsApp') !== false || 
                    strpos($log, 'webhook') !== false ||
                    strpos($log, '/api/webhook/whatsapp') !== false) {
                    
                    $logEntry = [
                        'timestamp' => $this->extractTimestamp($log),
                        'message' => $log,
                        'type' => $this->categorizeLog($log),
                        'severity' => $this->extractSeverity($log),
                        'structured_data' => $this->extractStructuredData($log)
                    ];
                    
                    $webhookLogs[] = $logEntry;
                    
                    // Categorize by type
                    if ($logEntry['type'] === 'error') {
                        $errorLogs[] = $logEntry;
                    } elseif ($logEntry['type'] === 'message') {
                        $messageLogs[] = $logEntry;
                    }
                }
            }

            return response()->json([
                'logs' => array_reverse($webhookLogs), // Most recent first
                'error_logs' => array_reverse($errorLogs),
                'message_logs' => array_reverse($messageLogs),
                'received_messages' => $this->getReceivedMessages(), // Add received messages
                'count' => count($webhookLogs),
                'error_count' => count($errorLogs),
                'message_count' => count($messageLogs),
                'received_count' => count($this->getReceivedMessages()),
                'last_updated' => now()->toDateTimeString(),
                'statistics' => $this->getLogStatistics($webhookLogs)
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Clear webhook logs
     */
    public function clearLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                file_put_contents($logFile, '');
            }
            return response()->json(['message' => 'Logs cleared successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get last N lines from file
     */
    private function getLastLines($file, $lines)
    {
        $handle = fopen($file, "r");
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = array();
        
        while ($linecounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $linecounter--;
            if ($beginning) {
                rewind($handle);
            }
            $text[$lines - $linecounter - 1] = fgets($handle);
            if ($beginning) break;
        }
        fclose($handle);
        return array_reverse($text);
    }

    /**
     * Extract timestamp from log line
     */
    private function extractTimestamp($log)
    {
        preg_match('/\[(.*?)\]/', $log, $matches);
        return $matches[1] ?? 'Unknown';
    }

    /**
     * Test webhook endpoint
     */
    public function testWebhook(Request $request)
    {
        $webhookUrl = config('services.whatsapp.webhook_url');
        $verifyToken = config('services.whatsapp.verify_token');
        $challenge = 'admin_test_' . time();

        $testUrl = $webhookUrl . '?hub.mode=subscribe&hub.verify_token=' . urlencode($verifyToken) . '&hub.challenge=' . urlencode($challenge);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $testUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                return response()->json([
                    'success' => false,
                    'message' => 'cURL Error: ' . $error,
                    'http_code' => 0
                ]);
            }

            // More flexible response validation
            $success = ($httpCode == 200);
            $responseText = trim($response);
            
            // Check if response matches challenge (for verification) or if it's a valid webhook response
            if ($httpCode == 200) {
                if ($responseText === $challenge) {
                    $success = true;
                    $message = 'Webhook verification successful';
                } else if (strlen($responseText) > 0) {
                    $success = true;
                    $message = 'Webhook endpoint responding';
                } else {
                    $success = true;
                    $message = 'Webhook reachable (empty response)';
                }
            } else {
                $success = false;
                $message = 'Webhook test failed - HTTP ' . $httpCode;
            }

            // Log the test
            Log::info('Webhook test performed', [
                'url' => $testUrl,
                'http_code' => $httpCode,
                'response' => $responseText,
                'expected' => $challenge,
                'success' => $success,
                'message' => $message
            ]);

            return response()->json([
                'success' => $success,
                'message' => $message,
                'http_code' => $httpCode,
                'response' => $responseText,
                'expected' => $challenge
            ]);

        } catch (\Exception $e) {
            Log::error('Webhook test error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage(),
                'http_code' => 0
            ]);
        }
    }

    /**
     * Get webhook statistics
     */
    private function getWebhookStats()
    {
        try {
            // Get stats from database if available
            $stats = [
                'total_messages' => 0,
                'successful_deliveries' => 0,
                'failed_deliveries' => 0,
                'recent_activity' => [],
            ];

            // Try to get from WhatsApp conversations table
            try {
                if (Schema::hasTable('whatsapp_conversations')) {
                    $stats['total_messages'] = DB::table('whatsapp_conversations')->count();
                    $stats['recent_activity'] = DB::table('whatsapp_conversations')
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                }
            } catch (\Exception $e) {
                // Table doesn't exist, use default values
            }

            return $stats;
        } catch (\Exception $e) {
            Log::error('Error getting webhook stats: ' . $e->getMessage());
            return [
                'total_messages' => 0,
                'successful_deliveries' => 0,
                'failed_deliveries' => 0,
                'recent_activity' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Categorize log entry by type
     */
    private function categorizeLog($log)
    {
        if (strpos($log, 'ERROR') !== false || strpos($log, 'CRITICAL') !== false) {
            return 'error';
        } elseif (strpos($log, 'WhatsApp webhook received') !== false || 
                  strpos($log, 'incoming message') !== false ||
                  strpos($log, 'received message') !== false) {
            return 'message_received';
        } elseif (strpos($log, 'WhatsApp API call made') !== false ||
                  strpos($log, 'Test message sent') !== false ||
                  strpos($log, 'message sent') !== false) {
            return 'message_sent';
        } elseif (strpos($log, 'verification') !== false ||
                  strpos($log, 'webhook verification') !== false) {
            return 'verification';
        } elseif (strpos($log, 'message') !== false) {
            return 'message';
        } elseif (strpos($log, 'INFO') !== false) {
            return 'info';
        } else {
            return 'general';
        }
    }

    /**
     * Extract log severity level
     */
    private function extractSeverity($log)
    {
        if (strpos($log, 'CRITICAL') !== false) return 'critical';
        if (strpos($log, 'ERROR') !== false) return 'error';
        if (strpos($log, 'WARNING') !== false) return 'warning';
        if (strpos($log, 'INFO') !== false) return 'info';
        if (strpos($log, 'DEBUG') !== false) return 'debug';
        return 'unknown';
    }

    /**
     * Extract structured data from log entry
     */
    private function extractStructuredData($log)
    {
        $data = [];
        
        // Try to extract JSON data from log
        if (preg_match('/\{.*\}/', $log, $matches)) {
            try {
                $jsonData = json_decode($matches[0], true);
                if ($jsonData) {
                    $data = $jsonData;
                }
            } catch (\Exception $e) {
                // Ignore JSON parsing errors
            }
        }
        
        // Extract phone numbers (Indian format)
        if (preg_match('/\b(\+?91)?[6-9]\d{9}\b/', $log, $matches)) {
            $data['phone_number'] = $matches[0];
        } elseif (preg_match('/\b\d{10,15}\b/', $log, $matches)) {
            $data['phone_number'] = $matches[0];
        }
        
        // Extract message types
        if (preg_match('/"type":\s*"([^"]+)"/', $log, $matches)) {
            $data['message_type'] = $matches[1];
        }
        
        // Extract message content
        if (preg_match('/"text":\s*"([^"]+)"/', $log, $matches)) {
            $data['message_text'] = $matches[1];
        } elseif (preg_match('/"body":\s*"([^"]+)"/', $log, $matches)) {
            $data['message_text'] = $matches[1];
        }
        
        // Extract sender information
        if (preg_match('/"from":\s*"([^"]+)"/', $log, $matches)) {
            $data['from'] = $matches[1];
        }
        
        // Extract message ID
        if (preg_match('/"id":\s*"([^"]+)"/', $log, $matches)) {
            $data['message_id'] = $matches[1];
        }
        
        // Extract timestamp
        if (preg_match('/"timestamp":\s*"?(\d+)"?/', $log, $matches)) {
            $data['message_timestamp'] = $matches[1];
        }
        
        return $data;
    }

    /**
     * Get statistics from logs
     */
    private function getLogStatistics($logs)
    {
        $stats = [
            'total_logs' => count($logs),
            'by_type' => [],
            'by_severity' => [],
            'recent_errors' => [],
            'message_flow' => []
        ];
        
        foreach ($logs as $log) {
            // Count by type
            $type = $log['type'];
            if (!isset($stats['by_type'][$type])) {
                $stats['by_type'][$type] = 0;
            }
            $stats['by_type'][$type]++;
            
            // Count by severity
            $severity = $log['severity'];
            if (!isset($stats['by_severity'][$severity])) {
                $stats['by_severity'][$severity] = 0;
            }
            $stats['by_severity'][$severity]++;
            
            // Collect recent errors
            if ($log['severity'] === 'error' || $log['severity'] === 'critical') {
                $stats['recent_errors'][] = $log;
            }
        }
        
        // Limit recent errors to last 5
        $stats['recent_errors'] = array_slice($stats['recent_errors'], 0, 5);
        
        return $stats;
    }

    /**
     * Send a test message to demonstrate webhook flow
     */
    public function sendTestMessage(Request $request)
    {
        try {
            $phoneNumber = $request->input('phone_number', '918519931876');
            $message = $request->input('message', 'Test message from FreeDoctorCORPO webhook monitor at ' . now()->format('Y-m-d H:i:s'));

            // Log the test message attempt
            Log::info('Admin initiated test message', [
                'phone_number' => $phoneNumber,
                'message' => $message,
                'admin_user' => auth('admin')->user()->name ?? 'Unknown',
                'timestamp' => now()->toDateTimeString()
            ]);

            // Get WhatsApp configuration
            $accessToken = env('WHATSAPP_API_KEY') ?: env('WHATSAPP_CLOUD_TOKEN');
            $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID') ?: env('WHATSAPP_CLOUD_PHONE_NUMBER_ID');
            
            // Check if WhatsApp is properly configured
            if (empty($accessToken) || empty($phoneNumberId) || 
                $accessToken === 'your_whatsapp_cloud_api_token_here' ||
                $phoneNumberId === 'your_phone_number_id_here') {
                
                Log::warning('WhatsApp not configured properly', [
                    'access_token_set' => !empty($accessToken),
                    'phone_number_id_set' => !empty($phoneNumberId),
                    'access_token_placeholder' => $accessToken === 'your_whatsapp_cloud_api_token_here',
                    'phone_id_placeholder' => $phoneNumberId === 'your_phone_number_id_here'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp API not configured. Please update .env with real WhatsApp credentials.',
                    'phone_number' => $phoneNumber,
                    'sent_message' => $message,
                    'configuration_needed' => [
                        'WHATSAPP_API_KEY' => 'Your WhatsApp Business API Access Token',
                        'WHATSAPP_PHONE_NUMBER_ID' => 'Your WhatsApp Business Phone Number ID'
                    ],
                    'current_config' => [
                        'access_token' => $accessToken ? 'Set (length: ' . strlen($accessToken) . ')' : 'Not set',
                        'phone_number_id' => $phoneNumberId ?: 'Not set'
                    ]
                ]);
            }

            // Send actual WhatsApp message
            $result = $this->sendWhatsAppMessage($phoneNumber, $message, $accessToken, $phoneNumberId);
            
            if ($result['success']) {
                Log::info('Test message sent successfully via WhatsApp API', [
                    'phone_number' => $phoneNumber,
                    'message' => $message,
                    'whatsapp_message_id' => $result['message_id'] ?? 'unknown',
                    'api_response' => $result['response'] ?? null
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Test message sent successfully via WhatsApp API!',
                    'phone_number' => $phoneNumber,
                    'sent_message' => $message,
                    'whatsapp_message_id' => $result['message_id'] ?? null,
                    'timestamp' => now()->toDateTimeString()
                ]);
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone_number' => $phoneNumber,
                    'message' => $message,
                    'error' => $result['error'] ?? 'Unknown error',
                    'api_response' => $result['response'] ?? null
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send WhatsApp message: ' . ($result['error'] ?? 'Unknown error'),
                    'phone_number' => $phoneNumber,
                    'api_error' => $result['error'] ?? null,
                    'api_response' => $result['response'] ?? null
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Test message send failed: ' . $e->getMessage(), [
                'phone_number' => $phoneNumber ?? 'unknown',
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent logs for immediate display
     */
    private function getRecentLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!file_exists($logFile)) {
                return [];
            }

            // Get last 50 lines for quick display
            $logs = $this->getLastLines($logFile, 50);
            $recentLogs = [];
            
            foreach ($logs as $log) {
                if (strpos($log, 'WhatsApp') !== false || 
                    strpos($log, 'webhook') !== false ||
                    strpos($log, '/api/webhook/whatsapp') !== false) {
                    
                    $recentLogs[] = [
                        'timestamp' => $this->extractTimestamp($log),
                        'message' => $log,
                        'type' => $this->categorizeLog($log),
                        'severity' => $this->extractSeverity($log)
                    ];
                }
            }

            return array_reverse(array_slice($recentLogs, -10)); // Last 10 webhook-related logs
        } catch (\Exception $e) {
            Log::error('Error getting recent logs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get received messages from database and logs
     */
    public function getReceivedMessages()
    {
        try {
            $receivedMessages = [];
            
            // Get from database if tables exist
            try {
                if (Schema::hasTable('whatsapp_conversations')) {
                    $dbMessages = DB::table('whatsapp_conversations')
                        ->whereNotNull('incoming_message')
                        ->orWhere('direction', 'incoming')
                        ->orderBy('created_at', 'desc')
                        ->limit(20)
                        ->get();
                    
                    foreach ($dbMessages as $msg) {
                        $receivedMessages[] = [
                            'id' => $msg->id ?? 'db-' . uniqid(),
                            'phone_number' => $msg->phone_number ?? $msg->from_number ?? 'Unknown',
                            'message' => $msg->incoming_message ?? $msg->message ?? 'No message content',
                            'timestamp' => $msg->created_at ?? $msg->received_at ?? now(),
                            'source' => 'database',
                            'status' => $msg->status ?? 'received',
                            'message_type' => $msg->message_type ?? 'text',
                            'webhook_data' => $msg->webhook_data ?? null
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Table might not exist
            }

            // Also get from recent logs
            $logMessages = $this->extractReceivedMessagesFromLogs();
            $receivedMessages = array_merge($receivedMessages, $logMessages);
            
            // Sort by timestamp, most recent first
            usort($receivedMessages, function($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });

            return array_slice($receivedMessages, 0, 30); // Last 30 messages
            
        } catch (\Exception $e) {
            Log::error('Error getting received messages: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get received messages via AJAX
     */
    public function getReceivedMessagesAjax(Request $request)
    {
        try {
            $messages = $this->getReceivedMessages();
            
            return response()->json([
                'success' => true,
                'messages' => $messages,
                'count' => count($messages),
                'last_updated' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extract received messages from log files
     */
    private function extractReceivedMessagesFromLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!file_exists($logFile)) {
                return [];
            }

            $logs = $this->getLastLines($logFile, 500); // Get more lines for message extraction
            $extractedMessages = [];
            
            foreach ($logs as $log) {
                // Look for webhook received messages
                if (strpos($log, 'WhatsApp webhook received') !== false || 
                    strpos($log, 'incoming') !== false ||
                    strpos($log, 'received message') !== false) {
                    
                    $timestamp = $this->extractTimestamp($log);
                    $structured = $this->extractStructuredData($log);
                    
                    // Try to extract message content and phone number
                    $phoneNumber = 'Unknown';
                    $messageContent = 'Message received (content not parsed)';
                    
                    // Extract phone number patterns
                    if (preg_match('/\b(\+?91)?[6-9]\d{9}\b/', $log, $matches)) {
                        $phoneNumber = $matches[0];
                    } elseif (isset($structured['phone_number'])) {
                        $phoneNumber = $structured['phone_number'];
                    } elseif (isset($structured['from'])) {
                        $phoneNumber = $structured['from'];
                    }
                    
                    // Extract message content
                    if (preg_match('/"text":\s*"([^"]+)"/', $log, $matches)) {
                        $messageContent = $matches[1];
                    } elseif (preg_match('/"body":\s*"([^"]+)"/', $log, $matches)) {
                        $messageContent = $matches[1];
                    } elseif (preg_match('/message.*?["\']([^"\']{5,})["\']/', $log, $matches)) {
                        $messageContent = $matches[1];
                    }
                    
                    $extractedMessages[] = [
                        'id' => 'log-' . md5($log . $timestamp),
                        'phone_number' => $phoneNumber,
                        'message' => $messageContent,
                        'timestamp' => $timestamp,
                        'source' => 'log_file',
                        'status' => 'logged',
                        'message_type' => $structured['message_type'] ?? 'text',
                        'raw_log' => $log,
                        'webhook_data' => $structured
                    ];
                }
            }
            
            return $extractedMessages;
            
        } catch (\Exception $e) {
            Log::error('Error extracting messages from logs: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Send WhatsApp message via WhatsApp Business API
     */
    private function sendWhatsAppMessage($phoneNumber, $message, $accessToken, $phoneNumberId)
    {
        try {
            // Format phone number (remove any non-digits and ensure it starts with country code)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            if (!str_starts_with($phoneNumber, '91') && strlen($phoneNumber) == 10) {
                $phoneNumber = '91' . $phoneNumber; // Add India country code if missing
            }

            // WhatsApp Cloud API endpoint (using v23.0)
            $apiUrl = "https://graph.facebook.com/v23.0/{$phoneNumberId}/messages";
            
            // Message payload
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $phoneNumber,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ];

            // Headers
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ];

            // Send via cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Log the API call
            Log::info('WhatsApp API call made', [
                'url' => $apiUrl,
                'phone_number' => $phoneNumber,
                'message_length' => strlen($message),
                'http_code' => $httpCode,
                'curl_error' => $curlError,
                'response_length' => strlen($response)
            ]);

            if ($curlError) {
                return [
                    'success' => false,
                    'error' => 'cURL Error: ' . $curlError,
                    'response' => null
                ];
            }

            $responseData = json_decode($response, true);

            if ($httpCode == 200 && isset($responseData['messages'][0]['id'])) {
                return [
                    'success' => true,
                    'message_id' => $responseData['messages'][0]['id'],
                    'response' => $responseData
                ];
            } else {
                $errorMessage = 'HTTP ' . $httpCode;
                if (isset($responseData['error']['message'])) {
                    $errorMessage .= ': ' . $responseData['error']['message'];
                } elseif (isset($responseData['error'])) {
                    $errorMessage .= ': ' . json_encode($responseData['error']);
                }

                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'response' => $responseData,
                    'http_code' => $httpCode
                ];
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp send exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Exception: ' . $e->getMessage(),
                'response' => null
            ];
        }
    }
    
    /**
     * Debug page for webhook testing
     */
    public function debug()
    {
        // Get environment information
        $envInfo = [
            'webhook_url' => env('WHATSAPP_WEBHOOK_URL', 'https://freedoctor.in/api/webhook/whatsapp'),
            'verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'FreeDoctor2025SecureToken'),
            'app_url' => env('APP_URL', config('app.url')),
            'local_webhook' => url('/api/webhook/whatsapp'),
            'local_test_url' => url('/api/webhook/whatsapp') . '?hub.mode=subscribe&hub.verify_token=' . urlencode(env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'FreeDoctor2025SecureToken')) . '&hub.challenge=TEST123'
        ];

        return view('admin.webhook-debug', compact('envInfo'));
    }

    /**
     * Get live logs with real-time updates
     */
    public function getLiveLogs(Request $request)
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (!file_exists($logFile)) {
                return response()->json([
                    'logs' => [],
                    'message' => 'Log file not found',
                    'timestamp' => now()->toISOString()
                ]);
            }

            // Get last 100 lines for live monitoring
            $logs = $this->getLastLines($logFile, 100);
            
            // Filter for WhatsApp webhook logs with emoji indicators
            $webhookLogs = [];
            
            foreach ($logs as $log) {
                if (strpos($log, 'WhatsApp') !== false || 
                    strpos($log, 'webhook') !== false ||
                    strpos($log, '🔔') !== false ||
                    strpos($log, '📨') !== false ||
                    strpos($log, '💬') !== false ||
                    strpos($log, '✅') !== false ||
                    strpos($log, '❌') !== false) {
                    
                    $logEntry = [
                        'timestamp' => $this->extractTimestamp($log),
                        'message' => $log,
                        'type' => $this->categorizeLog($log),
                        'severity' => $this->extractSeverity($log),
                        'emoji' => $this->extractEmoji($log),
                        'is_webhook' => true
                    ];
                    
                    $webhookLogs[] = $logEntry;
                }
            }

            return response()->json([
                'logs' => array_reverse(array_slice($webhookLogs, -20)), // Last 20 webhook logs
                'count' => count($webhookLogs),
                'timestamp' => now()->toISOString(),
                'log_file_size' => file_exists($logFile) ? filesize($logFile) : 0,
                'log_file_modified' => file_exists($logFile) ? date('Y-m-d H:i:s', filemtime($logFile)) : 'N/A'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Extract emoji from log entry for better visualization
     */
    private function extractEmoji($log)
    {
        if (preg_match('/([🔔📨💬✅❌🤖📝📤ℹ️⚠️⏭️])/u', $log, $matches)) {
            return $matches[1];
        }
        return '';
    }
}
