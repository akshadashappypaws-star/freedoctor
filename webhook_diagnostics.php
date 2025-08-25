<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Webhook v23.0 Diagnostics</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .status-box { padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        .warning { background: #fff3cd; border-color: #ffc107; }
        .info { background: #d1ecf1; border-color: #17a2b8; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; overflow-x: auto; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 WhatsApp Webhook v23.0 Diagnostics</h1>
        
        <div class="section">
            <h2>📋 Current Configuration</h2>
            <div class="grid">
                <div>
                    <h3>Environment</h3>
                    <div class="code">
                        <strong>App URL:</strong> <?php echo $_ENV['APP_URL'] ?? env('APP_URL', 'Not set'); ?><br>
                        <strong>Environment:</strong> <?php echo $_ENV['APP_ENV'] ?? env('APP_ENV', 'local'); ?><br>
                        <strong>Webhook URL:</strong> <?php echo $_ENV['WHATSAPP_WEBHOOK_URL'] ?? env('WHATSAPP_WEBHOOK_URL', 'Not set'); ?><br>
                        <strong>Verify Token:</strong> <?php echo ($_ENV['WHATSAPP_WEBHOOK_VERIFY_TOKEN'] ?? env('WHATSAPP_WEBHOOK_VERIFY_TOKEN')) ? 'SET' : 'NOT SET'; ?>
                    </div>
                </div>
                <div>
                    <h3>WhatsApp API Config</h3>
                    <div class="code">
                        <strong>Phone Number ID:</strong> <?php echo ($_ENV['WHATSAPP_PHONE_NUMBER_ID'] ?? env('WHATSAPP_PHONE_NUMBER_ID')) ? 'SET' : 'NOT SET'; ?><br>
                        <strong>Access Token:</strong> <?php echo ($_ENV['WHATSAPP_CLOUD_TOKEN'] ?? env('WHATSAPP_CLOUD_TOKEN')) ? 'SET' : 'NOT SET'; ?><br>
                        <strong>Business Account:</strong> <?php echo ($_ENV['WHATSAPP_BUSINESS_ACCOUNT_ID'] ?? env('WHATSAPP_BUSINESS_ACCOUNT_ID')) ? 'SET' : 'NOT SET'; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>🔗 Webhook Endpoints</h2>
            
            <div class="status-box info">
                <strong>Laravel Route:</strong> /webhook/whatsapp<br>
                <strong>Full URL:</strong> <code><?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/webhook/whatsapp</code>
            </div>

            <div class="status-box warning">
                <strong>Debug Script:</strong> webhook_debug_v23.php<br>
                <strong>Full URL:</strong> <code><?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/webhook_debug_v23.php</code>
            </div>
        </div>

        <div class="section">
            <h2>🧪 Quick Tests</h2>
            
            <div class="grid">
                <div>
                    <h3>Verification Test</h3>
                    <p>Test if your webhook responds to Meta's verification:</p>
                    <div class="code">
                        <a href="webhook_debug_v23.php?hub_mode=subscribe&hub_verify_token=FreeDoctor2025SecureToken&hub_challenge=TEST123" target="_blank" class="btn">
                            Test Debug Script Verification
                        </a>
                    </div>
                    <p><small>Should return: TEST123</small></p>
                </div>
                
                <div>
                    <h3>Laravel Route Test</h3>
                    <p>Test your Laravel webhook route:</p>
                    <div class="code">
                        <a href="webhook/whatsapp?hub_mode=subscribe&hub_verify_token=FreeDoctor2025SecureToken&hub_challenge=LARAVEL_TEST" target="_blank" class="btn">
                            Test Laravel Route
                        </a>
                    </div>
                    <p><small>Should return: LARAVEL_TEST</small></p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>📊 Common Issues & Solutions</h2>
            
            <div class="status-box error">
                <h3>❌ No Messages Received</h3>
                <strong>Possible Causes:</strong>
                <ul>
                    <li><strong>API Version:</strong> Using old API version (should be v23.0)</li>
                    <li><strong>Webhook URL:</strong> Not accessible from Meta servers</li>
                    <li><strong>Response Code:</strong> Not returning 200 OK</li>
                    <li><strong>Verification:</strong> Webhook verification failing</li>
                    <li><strong>Subscription:</strong> Not subscribed to message events</li>
                </ul>
            </div>

            <div class="status-box success">
                <h3>✅ Debugging Steps</h3>
                <ol>
                    <li><strong>Check webhook_debug.log:</strong> Look for incoming POST requests</li>
                    <li><strong>Verify Meta Dashboard:</strong> Check webhook delivery status</li>
                    <li><strong>Test locally:</strong> Use ngrok to expose local webhook</li>
                    <li><strong>Check logs:</strong> Laravel logs in storage/logs/laravel.log</li>
                    <li><strong>Update API version:</strong> Use v23.0 instead of older versions</li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2>📝 WhatsApp v23.0 Updates</h2>
            
            <div class="status-box info">
                <strong>What's New in v23.0:</strong>
                <ul>
                    <li>Enhanced message delivery tracking</li>
                    <li>Improved webhook payload structure</li>
                    <li>Better error handling and status codes</li>
                    <li>Support for new message types</li>
                    <li>Enhanced security features</li>
                </ul>
            </div>

            <div class="code">
                <strong>Update your API calls to use v23.0:</strong><br>
                OLD: https://graph.facebook.com/v18.0/{phone-number-id}/messages<br>
                NEW: https://graph.facebook.com/v23.0/{phone-number-id}/messages
            </div>
        </div>

        <div class="section">
            <h2>🔧 Next Steps</h2>
            
            <div class="status-box warning">
                <ol>
                    <li><strong>Update Meta Console:</strong> Set webhook URL to use v23.0</li>
                    <li><strong>Test debug script:</strong> Send a test message and check webhook_debug.log</li>
                    <li><strong>Verify database:</strong> Check if messages are being saved</li>
                    <li><strong>Check Laravel logs:</strong> Look for any errors or warnings</li>
                    <li><strong>Test with curl:</strong> Simulate webhook POST request</li>
                </ol>
            </div>
        </div>

        <div class="section">
            <h2>📋 Log Files to Check</h2>
            <div class="code">
                <strong>Webhook Debug Log:</strong> webhook_debug.log<br>
                <strong>Laravel Log:</strong> storage/logs/laravel.log<br>
                <strong>Apache/Nginx Error Log:</strong> Check server error logs<br>
                <strong>PHP Error Log:</strong> Check PHP error logs
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh page every 30 seconds when testing
        setInterval(() => {
            if (window.location.hash === '#testing') {
                window.location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
