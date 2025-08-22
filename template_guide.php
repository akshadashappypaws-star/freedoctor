<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\WhatsappTemplateValidator;

echo "=== Testing Template System (Safe Mode) ===\n";

try {
    echo "Step 1: Finding safe templates to send...\n";
    $templates = WhatsappTemplateValidator::getAllTemplatesWithParams();
    
    $safeTemplates = array_filter($templates, function($t) {
        return !$t['requires_params'];
    });
    
    echo "Safe templates (no parameters required):\n";
    foreach ($safeTemplates as $template) {
        echo "✅ {$template['name']} ({$template['category']})\n";
    }
    
    echo "\nTemplates requiring parameters:\n";
    $paramTemplates = array_filter($templates, function($t) {
        return $t['requires_params'];
    });
    
    foreach ($paramTemplates as $template) {
        echo "⚠️  {$template['name']} - needs: " . implode(', ', $template['required_params']) . "\n";
    }
    
    echo "\n=== For review_message Template ===\n";
    echo "To send review_message, you need to provide:\n";
    echo "- Parameter {{1}}: What type of experience/service\n";
    echo "- Examples: 'consultation', 'appointment', 'treatment', 'service'\n";
    
    echo "\n=== Integration Guide ===\n";
    echo "1. Before sending any template, call:\n";
    echo "   WhatsappTemplateValidator::validateTemplate(\$templateName, \$params)\n";
    echo "\n2. For templates with parameters, use:\n";
    echo "   \$whatsappService->sendMessage(\$phone, null, \$template, \$paramArray)\n";
    echo "\n3. For templates without parameters, use:\n";
    echo "   \$whatsappService->sendTemplate(\$phone, \$template)\n";
    
    echo "\n✅ Template validation system is ready!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
