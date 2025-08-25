<?php
/**
 * Simple WhatsApp Webhook Test Script
 * Use this for quick debugging when Laravel is not working
 * 
 * Save as: webhook_test.php
 * Access: https://yourdomain.com/webhook_test.php
 */

// Configuration
$VERIFY_TOKEN = 'FreeDoctor2025SecureToken'; // Must match your .env WHATSAPP_WEBHOOK_VERIFY_TOKEN
$LOG_FILE = 'webhook_debug.log';

// Helper function to log messages
function logMessage($message) {
    global $LOG_FILE;
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents($LOG_FILE, $logEntry, FILE_APPEND | LOCK_EX);
}

// Log all incoming requests
$method = $_SERVER['REQUEST_METHOD'];
$headers = getallheaders();
$input = file_get_contents('php://input');
$queryParams = $_GET;

logMessage("=== NEW WEBHOOK REQUEST ===");
logMessage("Method: $method");
logMessage("Headers: " . json_encode($headers));
logMessage("Query Params: " . json_encode($queryParams));
logMessage("Body: " . $input);
logMessage("IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
logMessage("User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'unknown'));

// Handle GET request (webhook verification)
if ($method === 'GET') {
    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';
    
    logMessage("Verification attempt - Mode: $mode, Token: $token, Challenge: $challenge");
    
    // Show status page if no verification parameters
    if (empty($mode) && empty($token) && empty($challenge)) {
        header('Content-Type: application/json');
        $status = [
            'status' => 'WEBHOOK_TEST_ACTIVE',
            'timestamp' => date('c'),
            'message' => 'Simple WhatsApp Webhook Test Script',
            'verify_token_configured' => !empty($VERIFY_TOKEN),
            'log_file' => $LOG_FILE,
            'test_url' => $_SERVER['REQUEST_URI'] . "?hub_mode=subscribe&hub_verify_token=" . urlencode($VERIFY_TOKEN) . "&hub_challenge=TEST123",
            'instructions' => [
                '1. Click the test_url above to verify webhook',
                '2. Send WhatsApp message to test POST handling',
                '3. Check ' . $LOG_FILE . ' for detailed logs'
            ]
        ];
        echo json_encode($status, JSON_PRETTY_PRINT);
        logMessage("Status page displayed");
        exit;
    }
    
    // Verify webhook
    if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
        logMessage("✅ Verification successful - returning challenge: $challenge");
        echo $challenge;
        exit;
    } else {
        logMessage("❌ Verification failed - Mode: $mode, Token match: " . ($token === $VERIFY_TOKEN ? 'YES' : 'NO'));
        http_response_code(403);
        echo json_encode([
            'error' => 'Verification failed',
            'mode' => $mode,
            'token_provided' => !empty($token),
            'token_matches' => $token === $VERIFY_TOKEN
        ]);
        exit;
    }
}

// Handle POST request (incoming messages)
if ($method === 'POST') {
    logMessage("📨 Processing POST request");
    
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        logMessage("❌ Invalid JSON received");
        http_response_code(400);
        echo "Invalid JSON";
        exit;
    }
    
    logMessage("Parsed JSON: " . json_encode($data, JSON_PRETTY_PRINT));
    
    // Extract message if present
    $entry = $data['entry'][0] ?? null;
    $changes = $entry['changes'][0] ?? null;
    $value = $changes['value'] ?? null;
    $messages = $value['messages'] ?? [];
    $contacts = $value['contacts'] ?? [];
    
    logMessage("Message extraction - Entry: " . ($entry ? 'exists' : 'null') . ", Changes: " . ($changes ? 'exists' : 'null') . ", Messages: " . count($messages));
    
    foreach ($messages as $message) {
        $from = $message['from'];
        $messageId = $message['id'] ?? 'unknown';
        $type = $message['type'] ?? 'unknown';
        
        $text = '';
        switch ($type) {
            case 'text':
                $text = $message['text']['body'] ?? '';
                break;
            case 'image':
                $text = '[Image]' . ($message['image']['caption'] ?? '');
                break;
            case 'document':
                $text = '[Document] ' . ($message['document']['filename'] ?? 'Unknown file');
                break;
            case 'location':
                $lat = $message['location']['latitude'] ?? 0;
                $lng = $message['location']['longitude'] ?? 0;
                $text = "[Location] Lat: $lat, Lng: $lng";
                break;
            default:
                $text = "[$type message]";
        }
        
        logMessage("💬 Message from $from (ID: $messageId, Type: $type): $text");
        
        // Save to separate messages log
        $messageLog = "messages.log";
        $messageEntry = date('Y-m-d H:i:s') . " | FROM: $from | TYPE: $type | MESSAGE: $text | ID: $messageId\n";
        file_put_contents($messageLog, $messageEntry, FILE_APPEND | LOCK_EX);
    }
    
    if (empty($messages)) {
        logMessage("⚠️ No messages found in webhook data - might be status update or other event");
    }
    
    logMessage("✅ Webhook processed successfully");
    
    // Always return 200 OK
    http_response_code(200);
    echo "EVENT_RECEIVED";
    exit;
}

// Handle other methods
logMessage("❌ Unsupported method: $method");
http_response_code(405);
echo "Method not allowed";
?>
