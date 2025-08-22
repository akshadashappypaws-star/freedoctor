<?php

require_once 'vendor/autoload.php';

// Test with Laravel app context
use Illuminate\Foundation\Application;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Testing WhatsappConversationController instantiation...\n";
    
    // Try to resolve from container
    $controller = app()->make('App\Http\Controllers\Admin\WhatsappConversationController');
    echo "✓ Controller instantiated successfully\n";
    
    // Check if method exists using Laravel's method resolution
    if (method_exists($controller, 'index')) {
        echo "✓ Index method detected via method_exists()\n";
    } else {
        echo "✗ Index method NOT detected via method_exists()\n";
    }
    
    // Test reflection
    $reflection = new ReflectionClass($controller);
    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
    
    echo "Public methods found in class:\n";
    foreach ($methods as $method) {
        if ($method->getDeclaringClass()->getName() === 'App\Http\Controllers\Admin\WhatsappConversationController') {
            echo "  - " . $method->getName() . "\n";
        }
    }
    
    // Test if we can call the method
    if ($reflection->hasMethod('index')) {
        echo "✓ Index method found via reflection\n";
        
        $indexMethod = $reflection->getMethod('index');
        echo "✓ Method signature: " . $indexMethod->getName() . "\n";
        echo "✓ Parameter count: " . $indexMethod->getNumberOfParameters() . "\n";
        
    } else {
        echo "✗ Index method NOT found via reflection\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
