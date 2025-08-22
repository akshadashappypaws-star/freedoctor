<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Create a test request
$request = Illuminate\Http\Request::create('/admin/whatsapp/conversations', 'GET');

try {
    echo "Testing route response...\n";
    
    $response = $kernel->handle($request);
    
    echo "✓ Route handled successfully\n";
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Response Type: " . get_class($response) . "\n";
    
    if ($response->getStatusCode() === 200) {
        echo "✓ Route is working properly!\n";
    } else {
        echo "✗ Route returned status: " . $response->getStatusCode() . "\n";
        echo "Content: " . substr($response->getContent(), 0, 200) . "...\n";
    }
    
} catch (Exception $e) {
    echo "✗ Route test failed: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
