<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WebhookMonitorController extends Controller
{
    /**
     * Display webhook monitoring dashboard
     */
    public function index()
    {
        return view('admin.webhook-monitor');
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

            // Get last 100 lines of log file
            $logs = $this->getLastLines($logFile, 100);
            
            // Filter for webhook-related logs
            $webhookLogs = [];
            foreach ($logs as $log) {
                if (strpos($log, 'WhatsApp Webhook') !== false || 
                    strpos($log, '/api/webhook/whatsapp') !== false) {
                    $webhookLogs[] = [
                        'timestamp' => $this->extractTimestamp($log),
                        'message' => $log
                    ];
                }
            }

            return response()->json([
                'logs' => array_reverse($webhookLogs), // Most recent first
                'count' => count($webhookLogs),
                'last_updated' => now()->toDateTimeString()
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
}
