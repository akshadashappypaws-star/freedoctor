<?php

/**
 * Comprehensive Webhook Testing System
 * This file provides a complete webhook testing and monitoring solution
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebhookTester
{
    private $baseUrl;
    private $ngrokUrl;
    
    public function __construct($baseUrl = 'http://127.0.0.1:8000')
    {
        $this->baseUrl = $baseUrl;
    }
    
    /**
     * Set ngrok URL for external webhook testing
     */
    public function setNgrokUrl($ngrokUrl)
    {
        $this->ngrokUrl = rtrim($ngrokUrl, '/');
        return $this;
    }
    
    /**
     * Test all webhook endpoints
     */
    public function testAllWebhooks()
    {
        echo "🚀 Starting Comprehensive Webhook Testing...\n\n";
        
        $results = [
            'whatsapp' => $this->testWhatsAppWebhook(),
            'payment' => $this->testPaymentWebhook(),
            'razorpay' => $this->testRazorpayWebhook(),
            'general' => $this->testGeneralWebhook()
        ];
        
        $this->displayResults($results);
        
        return $results;
    }
    
    /**
     * Test WhatsApp webhook
     */
    public function testWhatsAppWebhook()
    {
        echo "📱 Testing WhatsApp Webhook...\n";
        
        $testUrl = ($this->ngrokUrl ?: $this->baseUrl) . '/webhook/whatsapp';
        
        // Test webhook verification (GET request)
        $verifyResult = $this->testWebhookVerification($testUrl);
        
        // Test incoming message webhook (POST request)
        $messageResult = $this->testWhatsAppMessage($testUrl);
        
        // Test status update webhook
        $statusResult = $this->testWhatsAppStatus($testUrl);
        
        return [
            'verification' => $verifyResult,
            'message' => $messageResult,
            'status' => $statusResult
        ];
    }
    
    /**
     * Test webhook verification
     */
    private function testWebhookVerification($url)
    {
        $params = [
            'hub.mode' => 'subscribe',
            'hub.verify_token' => 'FreeDoctor2025SecureToken',
            'hub.challenge' => 'test_challenge_12345'
        ];
        
        $response = $this->makeRequest('GET', $url . '?' . http_build_query($params));
        
        return [
            'url' => $url,
            'method' => 'GET',
            'status' => $response['status'],
            'body' => $response['body'],
            'success' => $response['body'] === 'test_challenge_12345'
        ];
    }
    
    /**
     * Test WhatsApp message webhook
     */
    private function testWhatsAppMessage($url)
    {
        $payload = [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => '102290129340398',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '917741044366',
                                    'phone_number_id' => '109005472532677'
                                ],
                                'contacts' => [
                                    [
                                        'profile' => [
                                            'name' => 'Test User'
                                        ],
                                        'wa_id' => '919876543210'
                                    ]
                                ],
                                'messages' => [
                                    [
                                        'from' => '919876543210',
                                        'id' => 'wamid.test12345',
                                        'timestamp' => time(),
                                        'text' => [
                                            'body' => 'Hello, I need help finding a doctor'
                                        ],
                                        'type' => 'text'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', $url, $payload);
        
        return [
            'url' => $url,
            'method' => 'POST',
            'status' => $response['status'],
            'body' => $response['body'],
            'success' => $response['status'] === 200
        ];
    }
    
    /**
     * Test WhatsApp status webhook
     */
    private function testWhatsAppStatus($url)
    {
        $payload = [
            'object' => 'whatsapp_business_account',
            'entry' => [
                [
                    'id' => '102290129340398',
                    'changes' => [
                        [
                            'value' => [
                                'messaging_product' => 'whatsapp',
                                'metadata' => [
                                    'display_phone_number' => '917741044366',
                                    'phone_number_id' => '109005472532677'
                                ],
                                'statuses' => [
                                    [
                                        'id' => 'wamid.test12345',
                                        'status' => 'delivered',
                                        'timestamp' => time(),
                                        'recipient_id' => '919876543210'
                                    ]
                                ]
                            ],
                            'field' => 'messages'
                        ]
                    ]
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', $url, $payload);
        
        return [
            'url' => $url,
            'method' => 'POST',
            'status' => $response['status'],
            'body' => $response['body'],
            'success' => $response['status'] === 200
        ];
    }
    
    /**
     * Test payment webhook
     */
    public function testPaymentWebhook()
    {
        echo "💳 Testing Payment Webhook...\n";
        
        $testUrl = ($this->ngrokUrl ?: $this->baseUrl) . '/webhook/payment';
        
        $payload = [
            'event' => 'payment.captured',
            'account_id' => 'acc_test123',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_test12345',
                        'entity' => 'payment',
                        'amount' => 50000,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'order_id' => 'order_test123',
                        'created_at' => time()
                    ]
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', $testUrl, $payload);
        
        return [
            'url' => $testUrl,
            'method' => 'POST',
            'status' => $response['status'],
            'body' => $response['body'],
            'success' => $response['status'] === 200
        ];
    }
    
    /**
     * Test Razorpay webhook
     */
    public function testRazorpayWebhook()
    {
        echo "💰 Testing Razorpay Webhook...\n";
        
        $testUrl = ($this->ngrokUrl ?: $this->baseUrl) . '/webhook/razorpay';
        
        $payload = [
            'entity' => 'event',
            'account_id' => 'acc_test123',
            'event' => 'payment.captured',
            'contains' => ['payment'],
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_razorpay12345',
                        'entity' => 'payment',
                        'amount' => 50000,
                        'currency' => 'INR',
                        'status' => 'captured',
                        'order_id' => 'order_razorpay123',
                        'created_at' => time()
                    ]
                ]
            ],
            'created_at' => time()
        ];
        
        // Add Razorpay signature header
        $secret = 'test_webhook_secret';
        $signature = hash_hmac('sha256', json_encode($payload), $secret);
        
        $response = $this->makeRequest('POST', $testUrl, $payload, [
            'X-Razorpay-Signature' => $signature
        ]);
        
        return [
            'url' => $testUrl,
            'method' => 'POST',
            'status' => $response['status'],
            'body' => $response['body'],
            'success' => $response['status'] === 200
        ];
    }
    
    /**
     * Test general webhook
     */
    public function testGeneralWebhook()
    {
        echo "🔧 Testing General Webhook...\n";
        
        $testUrl = ($this->ngrokUrl ?: $this->baseUrl) . '/webhook/general';
        
        $payload = [
            'event' => 'test_event',
            'data' => [
                'message' => 'This is a test webhook',
                'timestamp' => time(),
                'source' => 'webhook_tester'
            ]
        ];
        
        $response = $this->makeRequest('POST', $testUrl, $payload);
        
        return [
            'url' => $testUrl,
            'method' => 'POST',
            'status' => $response['status'],
            'body' => $response['body'],
            'success' => $response['status'] === 200
        ];
    }
    
    /**
     * Make HTTP request
     */
    private function makeRequest($method, $url, $data = null, $headers = [])
    {
        $ch = curl_init();
        
        $defaultHeaders = [
            'Content-Type: application/json',
            'User-Agent: FreeDoctor-Webhook-Tester/1.0'
        ];
        
        foreach ($headers as $key => $value) {
            $defaultHeaders[] = "$key: $value";
        }
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $defaultHeaders,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            return [
                'status' => 0,
                'body' => "CURL Error: $error",
                'error' => true
            ];
        }
        
        return [
            'status' => $httpCode,
            'body' => $response,
            'error' => false
        ];
    }
    
    /**
     * Display test results
     */
    private function displayResults($results)
    {
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "📊 WEBHOOK TEST RESULTS\n";
        echo str_repeat("=", 70) . "\n\n";
        
        foreach ($results as $type => $typeResults) {
            echo "🔍 " . strtoupper($type) . " WEBHOOK:\n";
            echo str_repeat("-", 40) . "\n";
            
            if (is_array($typeResults) && isset($typeResults['url'])) {
                // Single test result
                $this->displaySingleResult($typeResults);
            } else {
                // Multiple test results
                foreach ($typeResults as $testName => $result) {
                    echo "  📝 $testName:\n";
                    $this->displaySingleResult($result, '    ');
                }
            }
            echo "\n";
        }
        
        echo "✅ Testing completed at " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 70) . "\n";
    }
    
    /**
     * Display single result
     */
    private function displaySingleResult($result, $indent = '  ')
    {
        $status = $result['success'] ? '✅ PASS' : '❌ FAIL';
        echo "{$indent}Status: $status\n";
        echo "{$indent}URL: {$result['url']}\n";
        echo "{$indent}Method: {$result['method']}\n";
        echo "{$indent}HTTP Code: {$result['status']}\n";
        
        if (!empty($result['body'])) {
            $body = is_string($result['body']) ? $result['body'] : json_encode($result['body']);
            $shortBody = strlen($body) > 100 ? substr($body, 0, 100) . '...' : $body;
            echo "{$indent}Response: $shortBody\n";
        }
        echo "\n";
    }
    
    /**
     * Generate webhook URLs for external services
     */
    public function generateWebhookUrls()
    {
        $baseUrl = $this->ngrokUrl ?: $this->baseUrl;
        
        return [
            'WhatsApp Business API' => "$baseUrl/webhook/whatsapp",
            'Payment Gateway' => "$baseUrl/webhook/payment", 
            'Razorpay' => "$baseUrl/webhook/razorpay",
            'General Webhook' => "$baseUrl/webhook/general",
            'Test Endpoint' => "$baseUrl/webhook/test"
        ];
    }
    
    /**
     * Display webhook URLs
     */
    public function displayWebhookUrls()
    {
        $urls = $this->generateWebhookUrls();
        
        echo "\n" . str_repeat("=", 70) . "\n";
        echo "🔗 WEBHOOK URLs FOR EXTERNAL SERVICES\n";
        echo str_repeat("=", 70) . "\n\n";
        
        foreach ($urls as $service => $url) {
            echo "📌 $service:\n";
            echo "   $url\n\n";
        }
        
        echo "💡 Copy these URLs to configure webhooks in external services\n";
        echo str_repeat("=", 70) . "\n";
    }
}

// Usage example
if (php_sapi_name() === 'cli') {
    echo "🎯 FreeDoctor Webhook Testing System\n";
    echo "====================================\n\n";
    
    $tester = new WebhookTester();
    
    // Check if ngrok URL is provided as command line argument
    if (isset($argv[1])) {
        $ngrokUrl = $argv[1];
        $tester->setNgrokUrl($ngrokUrl);
        echo "🌐 Using ngrok URL: $ngrokUrl\n\n";
    }
    
    // Display webhook URLs
    $tester->displayWebhookUrls();
    
    echo "\nPress Enter to start testing webhooks...";
    fgets(STDIN);
    
    // Run all tests
    $results = $tester->testAllWebhooks();
    
    echo "\n🎉 All webhook tests completed!\n";
    echo "Check the results above and configure your external services accordingly.\n";
}
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "GET request (verification): HTTP $http_code\n";
if ($http_code == 200 && $result == "test123") {
    echo "✅ Webhook verification is working\n";
} else {
    echo "❌ Webhook verification failed\n";
    echo "Response: $result\n";
}

// Test POST request (message simulation)
echo "\n4. Testing webhook message handling...\n";
$test_data = [
    'entry' => [
        [
            'changes' => [
                [
                    'value' => [
                        'messages' => [
                            [
                                'from' => '919876543210',
                                'id' => 'test_message_' . time(),
                                'text' => ['body' => 'hello'],
                                'timestamp' => time()
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhook_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($test_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: WhatsApp/1.0'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "POST request (message): HTTP $http_code\n";
if ($http_code == 200) {
    echo "✅ Webhook message handling is working\n";
    echo "Response: $result\n";
} else {
    echo "❌ Webhook message handling failed\n";
    echo "Response: $result\n";
}

echo "\n📋 Summary:\n";
echo "===========\n";
echo "Webhook URL: $webhook_url\n";
echo "Verify Token: FreeDoctor2025SecureToken\n";
echo "Laravel Status: " . ($http_code == 200 ? "✅ Running" : "❌ Down") . "\n";
echo "\n📱 To test with real WhatsApp:\n";
echo "1. Update webhook URL in Meta Business Manager\n";
echo "2. Send a message to your WhatsApp Business number\n";
echo "3. Check your admin panel for new conversations\n";

?>
