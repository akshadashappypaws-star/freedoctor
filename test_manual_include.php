<?php

echo "Testing basic class existence...\n";

// Test without Laravel bootstrap first
spl_autoload_register(function ($class) {
    if (strpos($class, 'App\\') === 0) {
        $file = __DIR__ . '/' . str_replace(['App\\', '\\'], ['app/', '/'], $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Try to manually require the controller
require_once __DIR__ . '/app/Http/Controllers/Controller.php';
require_once __DIR__ . '/app/Http/Controllers/Admin/WhatsappConversationController.php';

echo "Files included\n";

try {
    $class = 'App\\Http\\Controllers\\Admin\\WhatsappConversationController';
    
    if (class_exists($class, false)) {
        echo "✓ Class exists after manual include\n";
        
        $reflection = new ReflectionClass($class);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        
        echo "Methods found:\n";
        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() === $class) {
                echo "  - " . $method->getName() . "\n";
            }
        }
        
    } else {
        echo "✗ Class not found even after manual include\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
