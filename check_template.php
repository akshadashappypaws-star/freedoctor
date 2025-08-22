<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsappTemplate;

echo "=== Template Parameter Analysis ===\n";

try {
    $template = WhatsappTemplate::where('name', 'review_message')->first();
    
    if (!$template) {
        echo "❌ review_message template not found\n";
        exit(1);
    }
    
    echo "Template: {$template->name}\n";
    echo "Status: {$template->status}\n";
    echo "Language: {$template->language}\n\n";
    
    // Check parameters
    $parameters = $template->parameters;
    if ($parameters) {
        echo "📋 Parameters found:\n";
        if (is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        }
        print_r($parameters);
    } else {
        echo "✅ No parameters required - template can be sent as-is\n";
    }
    
    // Check components for dynamic content
    $components = $template->components;
    if ($components) {
        echo "\n📋 Components analysis:\n";
        if (is_string($components)) {
            $components = json_decode($components, true);
        }
        
        $hasParameters = false;
        foreach ($components as $index => $component) {
            echo "Component {$index}: {$component['type']}\n";
            
            if (isset($component['text'])) {
                $text = $component['text'];
                // Check for parameter placeholders like {{1}}, {{2}}, etc.
                if (preg_match_all('/\{\{(\d+)\}\}/', $text, $matches)) {
                    $hasParameters = true;
                    echo "  ⚠️  Found parameters: " . implode(', ', array_unique($matches[1])) . "\n";
                    echo "  📝 Text: " . substr($text, 0, 100) . "...\n";
                } else {
                    echo "  ✅ No parameters in this component\n";
                }
            }
        }
        
        if (!$hasParameters) {
            echo "\n✅ Template has no parameter placeholders - safe to send without data\n";
        } else {
            echo "\n❌ Template requires parameter data to be sent\n";
        }
    }
    
    echo "\n=== Content Preview ===\n";
    echo substr($template->content, 0, 300) . "...\n";
    
    echo "\n=== All Templates Summary ===\n";
    $allTemplates = WhatsappTemplate::all();
    foreach ($allTemplates as $t) {
        $paramCount = 0;
        if ($t->parameters) {
            $params = is_string($t->parameters) ? json_decode($t->parameters, true) : $t->parameters;
            $paramCount = is_array($params) ? count($params) : 0;
        }
        echo "- {$t->name} (parameters: $paramCount)\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
