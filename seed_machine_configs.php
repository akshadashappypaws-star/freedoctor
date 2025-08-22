<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\WorkflowMachineConfig;

echo "Seeding machine configurations...\n";

$configs = [
    [
        'machine_type' => 'ai',
        'config_name' => 'openai_api_key',
        'config_json' => json_encode(['value' => '']),
        'description' => 'OpenAI API Key for AI responses',
        'is_active' => true,
        'priority' => 1
    ],
    [
        'machine_type' => 'ai',
        'config_name' => 'model',
        'config_json' => json_encode(['value' => 'gpt-3.5-turbo']),
        'description' => 'AI Model to use',
        'is_active' => true,
        'priority' => 1
    ],
    [
        'machine_type' => 'template',
        'config_name' => 'welcome_message',
        'config_json' => json_encode(['value' => 'Welcome to our service!']),
        'description' => 'Default welcome message template',
        'is_active' => true,
        'priority' => 1
    ],
    [
        'machine_type' => 'function',
        'config_name' => 'webhook_url',
        'config_json' => json_encode(['value' => '']),
        'description' => 'Webhook URL for function calls',
        'is_active' => true,
        'priority' => 1
    ],
    [
        'machine_type' => 'datatable',
        'config_name' => 'max_rows',
        'config_json' => json_encode(['value' => 1000]),
        'description' => 'Maximum rows to process in data tables',
        'is_active' => true,
        'priority' => 1
    ],
    [
        'machine_type' => 'visualization',
        'config_name' => 'chart_library',
        'config_json' => json_encode(['value' => 'chartjs']),
        'description' => 'Chart library for visualizations',
        'is_active' => true,
        'priority' => 1
    ]
];

foreach ($configs as $config) {
    try {
        $result = WorkflowMachineConfig::updateOrCreate(
            [
                'machine_type' => $config['machine_type'],
                'config_name' => $config['config_name']
            ],
            $config
        );
        
        echo "✅ Created: {$config['machine_type']}.{$config['config_name']}\n";
    } catch (Exception $e) {
        echo "❌ Error creating {$config['machine_type']}.{$config['config_name']}: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 Seeding completed!\n";
echo "Total configurations: " . WorkflowMachineConfig::count() . "\n";
