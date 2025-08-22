<?php

require_once 'vendor/autoload.php';

// Try to load the class directly
try {
    $className = 'App\\Http\\Controllers\\Admin\\WhatsappConversationController';
    
    echo "Attempting to load class: $className\n";
    
    // Check if class exists
    if (class_exists($className)) {
        echo "✓ Class exists\n";
        
        // Get reflection
        $reflection = new ReflectionClass($className);
        echo "✓ Reflection created\n";
        
        // Get all methods
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        echo "Public methods found:\n";
        foreach ($methods as $method) {
            echo "  - " . $method->getName() . " (defined in " . $method->getDeclaringClass()->getName() . ")\n";
        }
        
        // Check specifically for index method
        if ($reflection->hasMethod('index')) {
            echo "✓ Index method found via reflection\n";
            $indexMethod = $reflection->getMethod('index');
            echo "  - Declared in: " . $indexMethod->getDeclaringClass()->getName() . "\n";
            echo "  - File: " . $indexMethod->getFileName() . "\n";
            echo "  - Line: " . $indexMethod->getStartLine() . "\n";
        } else {
            echo "✗ Index method NOT found via reflection\n";
        }
        
        // Try method_exists
        if (method_exists($className, 'index')) {
            echo "✓ Index method exists via method_exists()\n";
        } else {
            echo "✗ Index method NOT found via method_exists()\n";
        }
        
    } else {
        echo "✗ Class does not exist\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
