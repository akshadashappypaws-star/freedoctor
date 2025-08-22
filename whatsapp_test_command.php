<?php

// Artisan command to test WhatsApp
// Usage: php artisan tinker then run this code

use App\Services\WhatsappCloudApiService;
use Illuminate\Support\Facades\Log;

echo "Starting WhatsApp test via Laravel...\n";

try {
    // Test 1: Check if service can be instantiated
    $service = app(WhatsappCloudApiService::class);
    echo "✅ Service created successfully\n";
    
    // Test 2: Try to send hello_world template (should always work)
    echo "Sending hello_world template...\n";
    
    $result = $service->sendTemplate('+918519931876', 'hello_world');
    
    if ($result && isset($result['success']) && $result['success']) {
        echo "✅ SUCCESS: hello_world sent! Message ID: " . $result['message_id'] . "\n";
    } else {
        echo "❌ FAILED: hello_world not sent\n";
        echo "Result: " . print_r($result, true) . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "Test completed. Check WhatsApp and Laravel logs.\n";
?>
