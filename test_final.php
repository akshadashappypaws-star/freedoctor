<?php

// Test if the controller class can be loaded by Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Test direct class instantiation
try {
    echo "Testing class loading...\n";
    
    $controllerClass = 'App\\Http\\Controllers\\Admin\\WhatsappConversationController';
    
    if (class_exists($controllerClass)) {
        echo "✓ Class exists in autoloader\n";
        
        $controller = app()->make($controllerClass);
        echo "✓ Controller instantiated successfully\n";
        
        if (method_exists($controller, 'index')) {
            echo "✓ Index method exists\n";
        } else {
            echo "✗ Index method not found\n";
        }
    } else {
        echo "✗ Class not found in autoloader\n";
    }
    
    // Test route handling
    echo "\nTesting route...\n";
    $request = Illuminate\Http\Request::create('/admin/whatsapp/conversations', 'GET');
    $response = $kernel->handle($request);
    
    echo "✓ Route response status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() == 302) {
        echo "✓ Redirecting to login (expected behavior)\n";
    } elseif ($response->getStatusCode() == 200) {
        echo "✓ Route working successfully\n";
    } else {
        echo "✗ Unexpected response code\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
