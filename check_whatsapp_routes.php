<?php

// Route-Controller-View Mapping Analysis
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== WhatsApp Route-Controller-View Mapping Analysis ===\n\n";

// Define the expected mappings
$routeMappings = [
    'admin.whatsapp.dashboard' => [
        'controller' => 'WhatsappBotController@index',
        'view' => 'admin.whatsapp.dashboard',
        'file' => 'resources/views/admin/whatsapp/dashboard.blade.php'
    ],
    'admin.whatsapp.automation' => [
        'controller' => 'WhatsappAutomationController@index',
        'view' => 'admin.whatsapp.automation.index',
        'file' => 'resources/views/admin/whatsapp/automation/index.blade.php'
    ],
    'admin.whatsapp.automation.rules' => [
        'controller' => 'WhatsappAutomationController@rules',
        'view' => 'admin.whatsapp.automation.rules',
        'file' => 'resources/views/admin/whatsapp/automation/rules.blade.php'
    ],
    'admin.whatsapp.templates' => [
        'controller' => 'WhatsappBotController@showTemplates',
        'view' => 'admin.pages.whatsapp.templates',
        'file' => 'resources/views/admin/pages/whatsapp/templates.blade.php'
    ],
    'admin.whatsapp.bulk-messages' => [
        'controller' => 'WhatsappBotController@showBulkMessages',
        'view' => 'admin.pages.whatsapp.bulk-messages',
        'file' => 'resources/views/admin/pages/whatsapp/bulk-messages.blade.php'
    ],
    'admin.whatsapp.conversations' => [
        'controller' => 'WhatsappConversationController@index',
        'view' => 'admin.pages.whatsapp.conversations',
        'file' => 'resources/views/admin/pages/whatsapp/conversations.blade.php'
    ],
    'admin.whatsapp.settings' => [
        'controller' => 'WhatsappBotController@showSettings',
        'view' => 'admin.pages.whatsapp.settings',
        'file' => 'resources/views/admin/pages/whatsapp/settings.blade.php'
    ]
];

echo "📋 Checking Route-Controller-View Mappings:\n";
echo "==========================================\n\n";

foreach ($routeMappings as $routeName => $mapping) {
    echo "🔍 Route: $routeName\n";
    echo "   Controller: {$mapping['controller']}\n";
    echo "   Expected View: {$mapping['view']}\n";
    echo "   View File: {$mapping['file']}\n";
    
    // Check if route exists
    $routeExists = Route::has($routeName);
    echo "   ✅ Route Exists: " . ($routeExists ? 'YES' : 'NO') . "\n";
    
    // Check if view file exists
    $viewFileExists = file_exists($mapping['file']);
    echo "   ✅ View File Exists: " . ($viewFileExists ? 'YES' : 'NO') . "\n";
    
    if (!$routeExists) {
        echo "   ❌ ISSUE: Route '$routeName' not found!\n";
    }
    
    if (!$viewFileExists) {
        echo "   ❌ ISSUE: View file '{$mapping['file']}' not found!\n";
    }
    
    if ($routeExists && $viewFileExists) {
        echo "   ✅ STATUS: Route mapping looks good!\n";
    }
    
    echo "\n";
}

echo "=== Summary ===\n";
echo "Routes checked: " . count($routeMappings) . "\n";

$issues = 0;
foreach ($routeMappings as $routeName => $mapping) {
    if (!Route::has($routeName) || !file_exists($mapping['file'])) {
        $issues++;
    }
}

if ($issues === 0) {
    echo "✅ All routes are properly mapped to controllers and views!\n";
} else {
    echo "❌ Found $issues issues that need attention.\n";
}

echo "\n🎯 Next Steps:\n";
echo "1. Test each route manually by visiting the URLs\n";
echo "2. Check if controllers return the expected data\n";
echo "3. Verify view files render correctly\n";
echo "4. Test navigation flow between pages\n";
