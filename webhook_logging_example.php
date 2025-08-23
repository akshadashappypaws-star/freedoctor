<?php

// This shows how to add logging to your WhatsappWebhookController.php

// 1. Add to the top of your controller:
// use Illuminate\Support\Facades\Log;

// 2. Add this to your handle() method:

/*
public function handle(Request $request)
{
    // Log incoming webhook request
    Log::info('WhatsApp Webhook Request', [
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'body' => $request->all(),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'timestamp' => now()->toDateTimeString()
    ]);

    // Your existing webhook code...
    
    return response('EVENT_RECEIVED', 200);
}
*/

echo "=== How to Monitor Webhooks on Hostinger ===\n\n";

echo "OPTION 1: Laravel Logs\n";
echo "- Logs are saved to: storage/logs/laravel.log\n";
echo "- View via Hostinger File Manager\n";
echo "- Download and check logs regularly\n\n";

echo "OPTION 2: Custom Dashboard\n";
echo "- Create a simple dashboard to view webhook logs\n";
echo "- Access via: https://postauto.shop/webhook/monitor\n\n";

echo "OPTION 3: Database Logging\n";
echo "- Log all requests to database table\n";
echo "- View via admin panel\n\n";

echo "OPTION 4: Real-time Monitoring\n";
echo "- Use Laravel Telescope (if installed)\n";
echo "- Or create custom real-time monitor\n";
