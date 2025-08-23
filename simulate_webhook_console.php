<?php
// Simulate webhook request to test the console monitoring
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Simulating Webhook Request for Console Monitoring...\n\n";

// Log a simulated webhook request
\Illuminate\Support\Facades\Log::info('WhatsApp Webhook Request Received', [
    'method' => 'POST',
    'source' => 'Console Test',
    'timestamp' => now()->toDateTimeString(),
    'data' => [
        'object' => 'whatsapp_business_account',
        'entry' => [
            [
                'id' => '12345',
                'changes' => [
                    [
                        'value' => [
                            'messaging_product' => 'whatsapp',
                            'metadata' => [
                                'display_phone_number' => '15550123456',
                                'phone_number_id' => '745838968612692'
                            ],
                            'messages' => [
                                [
                                    'from' => '919876543210',
                                    'id' => 'wamid.test123',
                                    'timestamp' => time(),
                                    'text' => [
                                        'body' => 'Hello from webhook console test!'
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
    ]
]);

echo "✅ Simulated webhook request logged successfully!\n";
echo "This should now appear in the admin console panel.\n\n";

echo "Console Features:\n";
echo "- Located on the 'Live Conversations' page in admin panel\n";
echo "- Small panel next to the page title (300px wide)\n";
echo "- Real-time monitoring every 5 seconds\n";
echo "- Test button to verify webhook connectivity\n";
echo "- Console-style logging with timestamps\n";
echo "- Uses WHATSAPP_WEBHOOK_URL from .env file\n";
echo "- Dark theme console with colored logs\n";
echo "- Auto-scrolling and keeps last 20 entries\n\n";

echo "To see the console:\n";
echo "1. Login to admin panel: http://127.0.0.1:8000/admin/login\n";
echo "2. Go to WhatsApp > Live Conversations\n";
echo "3. Look for the 'Webhook Console' panel on the right side\n";
echo "4. Click 'Test' button to test webhook connectivity\n";
?>
