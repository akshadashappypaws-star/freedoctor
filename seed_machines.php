<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Bootstrap Laravel app
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "🚀 Seeding machine configurations...\n";

    $configs = [
        [
            'machine_type' => 'ai',
            'config_name' => 'openai_api_key',
            'config_json' => json_encode(['description' => 'OpenAI API Key for GPT integration']),
            'is_active' => true,
            'priority' => 1,
            'version' => '1.0'
        ],
        [
            'machine_type' => 'ai',
            'config_name' => 'model_type',
            'config_json' => json_encode(['value' => 'gpt-3.5-turbo', 'description' => 'AI Model Type']),
            'is_active' => true,
            'priority' => 2,
            'version' => '1.0'
        ],
        [
            'machine_type' => 'function',
            'config_name' => 'webhook_url',
            'config_json' => json_encode(['description' => 'External webhook URL for function calls']),
            'is_active' => true,
            'priority' => 1,
            'version' => '1.0'
        ],
        [
            'machine_type' => 'datatable',
            'config_name' => 'max_rows',
            'config_json' => json_encode(['value' => 1000, 'description' => 'Maximum rows to process']),
            'is_active' => true,
            'priority' => 1,
            'version' => '1.0'
        ],
        [
            'machine_type' => 'template',
            'config_name' => 'default_language',
            'config_json' => json_encode(['value' => 'en', 'description' => 'Default template language']),
            'is_active' => true,
            'priority' => 1,
            'version' => '1.0'
        ],
        [
            'machine_type' => 'visualization',
            'config_name' => 'chart_library',
            'config_json' => json_encode(['value' => 'chartjs', 'description' => 'Chart library to use']),
            'is_active' => true,
            'priority' => 1,
            'version' => '1.0'
        ]
    ];

    foreach ($configs as $config) {
        $existing = DB::table('workflow_machine_configs')
            ->where('machine_type', $config['machine_type'])
            ->where('config_name', $config['config_name'])
            ->first();

        if (!$existing) {
            DB::table('workflow_machine_configs')->insert([
                'machine_type' => $config['machine_type'],
                'config_name' => $config['config_name'],
                'config_json' => $config['config_json'],
                'is_active' => $config['is_active'],
                'priority' => $config['priority'],
                'version' => $config['version'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ Inserted {$config['machine_type']} config: {$config['config_name']}\n";
        } else {
            echo "ℹ️ Config already exists: {$config['machine_type']}.{$config['config_name']}\n";
        }
    }

    echo "\n🎉 Machine configuration seeding completed!\n";
    echo "💡 Total configurations: " . count($configs) . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
