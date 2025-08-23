<?php
// API endpoint for fetching recent WhatsApp messages
// This file provides real-time message data for the monitor

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Include Laravel bootstrap (adjust path as needed)
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    }
    
    // Get parameters
    $since = $_GET['since'] ?? date('Y-m-d H:i:s', strtotime('-1 hour'));
    $limit = min(50, (int)($_GET['limit'] ?? 20));
    
    // Database connection
    $response = [
        'success' => true,
        'messages' => [],
        'timestamp' => date('Y-m-d H:i:s'),
        'debug' => [
            'since' => $since,
            'limit' => $limit
        ]
    ];
    
    try {
        // Try to get database connection from Laravel
        if (function_exists('config') && config('database.default')) {
            $messages = fetchMessagesFromLaravel($since, $limit);
        } else {
            $messages = fetchMessagesFromDatabase($since, $limit);
        }
        
        $response['messages'] = $messages;
        $response['count'] = count($messages);
        
    } catch (Exception $e) {
        error_log("Message fetch error: " . $e->getMessage());
        
        // Fallback to demo data if database fails
        $response['messages'] = generateDemoMessages($since, $limit);
        $response['demo_mode'] = true;
        $response['error'] = $e->getMessage();
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'error' => $e->getMessage(),
        'messages' => [],
        'demo_mode' => true
    ];
    
    // Provide demo data even on error
    $response['messages'] = generateDemoMessages($since ?? date('Y-m-d H:i:s', strtotime('-1 hour')), $limit ?? 10);
}

echo json_encode($response, JSON_PRETTY_PRINT);

function fetchMessagesFromLaravel($since, $limit) {
    try {
        // Use Laravel's database connection
        $db = app('db');
        
        // Query user_messages table (adjust table name as needed)
        $messages = $db->table('user_messages')
            ->select([
                'id',
                'phone',
                'message',
                'created_at as timestamp',
                'updated_at',
                'status',
                'message_type'
            ])
            ->where('created_at', '>', $since)
            ->where('direction', 'incoming') // Only incoming messages from users
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        return $messages->toArray();
        
    } catch (Exception $e) {
        error_log("Laravel database error: " . $e->getMessage());
        throw $e;
    }
}

function fetchMessagesFromDatabase($since, $limit) {
    try {
        // Direct database connection (fallback)
        $host = $_ENV['DB_HOST'] ?? env('DB_HOST', 'localhost');
        $dbname = $_ENV['DB_DATABASE'] ?? env('DB_DATABASE', 'freedoctor');
        $username = $_ENV['DB_USERNAME'] ?? env('DB_USERNAME', 'root');
        $password = $_ENV['DB_PASSWORD'] ?? env('DB_PASSWORD', '');
        
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
        $sql = "
            SELECT 
                id,
                phone,
                message,
                created_at as timestamp,
                updated_at,
                status,
                message_type
            FROM user_messages 
            WHERE created_at > :since 
            AND direction = 'incoming'
            ORDER BY created_at DESC 
            LIMIT :limit
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':since', $since);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
        
    } catch (Exception $e) {
        error_log("Direct database error: " . $e->getMessage());
        throw $e;
    }
}

function generateDemoMessages($since, $limit) {
    $demoMessages = [];
    $phones = ['+1234567890', '+9876543210', '+1122334455', '+5544332211', '+9988776655'];
    $messages = [
        'Hello, I need help with my appointment',
        'Can you check my prescription status?',
        'I want to book a consultation with Dr. Smith',
        'What are your clinic hours today?',
        'Is there availability for tomorrow?',
        'I need to reschedule my appointment',
        'Can I get a medical certificate?',
        'My symptoms are getting worse',
        'When is my next follow-up?',
        'I lost my prescription, can you help?',
        'Do you accept my insurance?',
        'I need urgent medical advice',
        'Can I book a video consultation?',
        'What medications are available?',
        'I want to cancel my appointment'
    ];
    
    $startTime = strtotime($since);
    $currentTime = time();
    
    // Generate some recent messages
    for ($i = 0; $i < min($limit, 5); $i++) {
        $messageTime = $currentTime - rand(0, min(3600, $currentTime - $startTime));
        
        $demoMessages[] = [
            'id' => time() . rand(1000, 9999) . $i,
            'phone' => $phones[array_rand($phones)],
            'message' => $messages[array_rand($messages)],
            'timestamp' => date('Y-m-d H:i:s', $messageTime),
            'status' => 'received',
            'message_type' => 'text',
            'demo' => true
        ];
    }
    
    // Sort by timestamp descending
    usort($demoMessages, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });
    
    return $demoMessages;
}

function env($key, $default = null) {
    // Simple env function for when Laravel isn't available
    $value = $_ENV[$key] ?? getenv($key);
    return $value !== false ? $value : $default;
}
?>
