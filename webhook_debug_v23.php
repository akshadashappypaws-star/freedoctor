<?php
/**
 * WhatsApp Webhook Debug Script for v23.0 API
 * This script helps diagnose webhook delivery issues
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log all requests to a file
$logFile = __DIR__ . '/webhook_debug.log';

// Get the request method and data
$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();
$rawData = file_get_contents("php://input");
$getData = $_GET;
$postData = $_POST;

// Create log entry
$logEntry = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $method,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'headers' => $headers,
    'get_data' => $getData,
    'post_data' => $postData,
    'raw_data' => $rawData,
    'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'unknown',
    'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
];

// Write to log file
file_put_contents($logFile, "=== WEBHOOK DEBUG LOG ===\n" . json_encode($logEntry, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND | LOCK_EX);

// Handle GET (verification)
if ($method === 'GET') {
    $mode = $_GET['hub_mode'] ?? $_GET['hub.mode'] ?? null;
    $token = $_GET['hub_verify_token'] ?? $_GET['hub.verify_token'] ?? null;
    $challenge = $_GET['hub_challenge'] ?? $_GET['hub.challenge'] ?? null;
    
    $expectedToken = 'FreeDoctor2025SecureToken'; // Your verify token
    
    if ($mode === 'subscribe' && $token === $expectedToken) {
        // Successful verification
        echo $challenge;
        file_put_contents($logFile, "✅ VERIFICATION SUCCESS: Returned challenge: $challenge\n", FILE_APPEND | LOCK_EX);
        exit;
    } else {
        // Verification failed
        file_put_contents($logFile, "❌ VERIFICATION FAILED: mode=$mode, token=$token, expected=$expectedToken\n", FILE_APPEND | LOCK_EX);
        http_response_code(403);
        echo "Verification failed";
        exit;
    }
}

// Handle POST (webhook data)
if ($method === 'POST') {
    file_put_contents($logFile, "📨 POST REQUEST RECEIVED\n", FILE_APPEND | LOCK_EX);
    
    // Decode JSON data
    $data = json_decode($rawData, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        file_put_contents($logFile, "❌ JSON DECODE ERROR: " . json_last_error_msg() . "\n", FILE_APPEND | LOCK_EX);
        http_response_code(400);
        echo "Invalid JSON";
        exit;
    }
    
    // Log the structure
    file_put_contents($logFile, "📊 DATA STRUCTURE:\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND | LOCK_EX);
    
    // Check for WhatsApp v23.0 structure
    $entry = $data['entry'][0] ?? null;
    $changes = $entry['changes'][0] ?? null;
    $value = $changes['value'] ?? null;
    $messages = $value['messages'] ?? [];
    $contacts = $value['contacts'] ?? [];
    $statuses = $value['statuses'] ?? [];
    
    file_put_contents($logFile, "🔍 STRUCTURE ANALYSIS:\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "- Entry exists: " . ($entry ? 'YES' : 'NO') . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "- Changes exists: " . ($changes ? 'YES' : 'NO') . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "- Value exists: " . ($value ? 'YES' : 'NO') . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "- Messages count: " . count($messages) . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "- Contacts count: " . count($contacts) . "\n", FILE_APPEND | LOCK_EX);
    file_put_contents($logFile, "- Statuses count: " . count($statuses) . "\n", FILE_APPEND | LOCK_EX);
    
    // Process messages
    foreach ($messages as $index => $message) {
        $from = $message['from'] ?? 'unknown';
        $type = $message['type'] ?? 'unknown';
        $id = $message['id'] ?? 'unknown';
        $timestamp = $message['timestamp'] ?? time();
        
        $content = '';
        switch ($type) {
            case 'text':
                $content = $message['text']['body'] ?? '';
                break;
            case 'image':
                $content = '[Image] ' . ($message['image']['caption'] ?? '');
                break;
            case 'location':
                $lat = $message['location']['latitude'] ?? 0;
                $lng = $message['location']['longitude'] ?? 0;
                $content = "[Location] $lat, $lng";
                break;
            default:
                $content = "[$type message]";
        }
        
        file_put_contents($logFile, "💬 MESSAGE #$index:\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - From: $from\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - Type: $type\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - Content: $content\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - ID: $id\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - Timestamp: $timestamp\n", FILE_APPEND | LOCK_EX);
    }
    
    // Process delivery statuses (v23.0 feature)
    foreach ($statuses as $index => $status) {
        $id = $status['id'] ?? 'unknown';
        $status_type = $status['status'] ?? 'unknown';
        $recipient = $status['recipient_id'] ?? 'unknown';
        
        file_put_contents($logFile, "📋 STATUS #$index:\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - Message ID: $id\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - Status: $status_type\n", FILE_APPEND | LOCK_EX);
        file_put_contents($logFile, "  - Recipient: $recipient\n", FILE_APPEND | LOCK_EX);
    }
    
    // Always respond with 200 OK
    http_response_code(200);
    echo "EVENT_RECEIVED";
    file_put_contents($logFile, "✅ RESPONDED WITH: EVENT_RECEIVED\n\n", FILE_APPEND | LOCK_EX);
    exit;
}

// Handle other methods
file_put_contents($logFile, "❓ UNSUPPORTED METHOD: $method\n", FILE_APPEND | LOCK_EX);
http_response_code(405);
echo "Method not allowed";
?>
