<?php

// Direct file inclusion test
echo "Testing direct file inclusion...\n";

$filePath = __DIR__ . '/app/Http/Controllers/Admin/WhatsappConversationController.php';
echo "File path: $filePath\n";
echo "File exists: " . (file_exists($filePath) ? 'YES' : 'NO') . "\n";

// Include the file directly
try {
    include_once $filePath;
    echo "File included successfully\n";
    
    // Test if class is defined
    if (class_exists('App\\Http\\Controllers\\Admin\\WhatsappConversationController', false)) {
        echo "Class found after inclusion\n";
        
        $reflection = new ReflectionClass('App\\Http\\Controllers\\Admin\\WhatsappConversationController');
        $methods = $reflection->getMethods();
        
        echo "All methods found:\n";
        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() === 'App\\Http\\Controllers\\Admin\\WhatsappConversationController') {
                echo "  - " . $method->getName() . " (line " . $method->getStartLine() . ")\n";
            }
        }
        
    } else {
        echo "Class NOT found after inclusion\n";
    }
    
} catch (Exception $e) {
    echo "Error including file: " . $e->getMessage() . "\n";
    echo "Error line: " . $e->getLine() . "\n";
}
