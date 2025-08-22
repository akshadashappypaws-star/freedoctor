<?php
// Quick script to create workflow tables
// Run this in browser: http://127.0.0.1:8000/create_workflow_table.php

require_once 'vendor/autoload.php';

// Use Laravel's database configuration
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    echo "<h2>Creating WhatsApp Workflow Tables...</h2>";
    
    // Create workflows table
    if (!Schema::hasTable('workflows')) {
        DB::statement("
            CREATE TABLE workflows (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                name varchar(255) NOT NULL,
                status enum('pending','running','completed','failed','paused') NOT NULL DEFAULT 'pending',
                created_by bigint(20) unsigned DEFAULT NULL,
                user_id bigint(20) unsigned DEFAULT NULL,
                whatsapp_number varchar(20) DEFAULT NULL,
                intent varchar(255) DEFAULT NULL,
                json_plan json DEFAULT NULL,
                context_data json DEFAULT NULL,
                current_step int(11) NOT NULL DEFAULT 0,
                total_steps int(11) NOT NULL DEFAULT 0,
                started_at timestamp NULL DEFAULT NULL,
                completed_at timestamp NULL DEFAULT NULL,
                execution_time_ms bigint(20) DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY workflows_status_index (status),
                KEY workflows_whatsapp_number_index (whatsapp_number)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ workflows table created<br>";
    } else {
        echo "ℹ️ workflows table already exists<br>";
    }
    
    // Create workflow_logs table
    if (!Schema::hasTable('workflow_logs')) {
        DB::statement("
            CREATE TABLE workflow_logs (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                workflow_id bigint(20) unsigned NOT NULL,
                step_name varchar(255) NOT NULL,
                step_number int(11) NOT NULL,
                machine_type enum('ai','function','datatable','template','visualization') NOT NULL,
                input_data json DEFAULT NULL,
                output_data json DEFAULT NULL,
                execution_time_ms bigint(20) DEFAULT NULL,
                status enum('pending','running','completed','failed') NOT NULL DEFAULT 'pending',
                error_message text DEFAULT NULL,
                metadata json DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY workflow_logs_workflow_id_index (workflow_id),
                KEY workflow_logs_status_index (status),
                KEY workflow_logs_machine_type_index (machine_type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ workflow_logs table created<br>";
    } else {
        echo "ℹ️ workflow_logs table already exists<br>";
    }
    
    // Create workflow_errors table
    if (!Schema::hasTable('workflow_errors')) {
        DB::statement("
            CREATE TABLE workflow_errors (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                workflow_id bigint(20) unsigned NOT NULL,
                step_name varchar(255) NOT NULL,
                error_code varchar(100) DEFAULT NULL,
                error_message text NOT NULL,
                error_type enum('validation','execution','timeout','api_error','system_error') NOT NULL DEFAULT 'execution',
                stack_trace text DEFAULT NULL,
                input_data json DEFAULT NULL,
                context_data json DEFAULT NULL,
                retry_count int(11) NOT NULL DEFAULT 0,
                resolved tinyint(1) NOT NULL DEFAULT 0,
                resolved_at timestamp NULL DEFAULT NULL,
                resolved_by bigint(20) unsigned DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY workflow_errors_workflow_id_index (workflow_id),
                KEY workflow_errors_error_type_index (error_type),
                KEY workflow_errors_resolved_index (resolved)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ workflow_errors table created<br>";
    } else {
        echo "ℹ️ workflow_errors table already exists<br>";
    }
    
    // Create workflow_performance_metrics table
    if (!Schema::hasTable('workflow_performance_metrics')) {
        DB::statement("
            CREATE TABLE workflow_performance_metrics (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                workflow_id bigint(20) unsigned NOT NULL,
                metric_name varchar(255) NOT NULL,
                metric_value decimal(10,4) NOT NULL,
                metric_type enum('time','count','rate','percentage','bytes') NOT NULL DEFAULT 'count',
                step_name varchar(255) DEFAULT NULL,
                machine_type enum('ai','function','datatable','template','visualization') DEFAULT NULL,
                recorded_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                metadata json DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY workflow_performance_metrics_workflow_id_index (workflow_id),
                KEY workflow_performance_metrics_metric_name_index (metric_name),
                KEY workflow_performance_metrics_machine_type_index (machine_type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ workflow_performance_metrics table created<br>";
    } else {
        echo "ℹ️ workflow_performance_metrics table already exists<br>";
    }
    
    // Create workflow_conversation_history table
    if (!Schema::hasTable('workflow_conversation_history')) {
        DB::statement("
            CREATE TABLE workflow_conversation_history (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                workflow_id bigint(20) unsigned NOT NULL,
                message_id varchar(255) DEFAULT NULL,
                message_type enum('incoming','outgoing','system') NOT NULL,
                message_content text NOT NULL,
                message_format enum('text','image','document','audio','video','interactive') NOT NULL DEFAULT 'text',
                sender varchar(255) DEFAULT NULL,
                recipient varchar(255) DEFAULT NULL,
                template_name varchar(255) DEFAULT NULL,
                template_parameters json DEFAULT NULL,
                delivery_status enum('pending','sent','delivered','read','failed') NOT NULL DEFAULT 'pending',
                timestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                metadata json DEFAULT NULL,
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                KEY workflow_conversation_history_workflow_id_index (workflow_id),
                KEY workflow_conversation_history_message_type_index (message_type),
                KEY workflow_conversation_history_delivery_status_index (delivery_status),
                KEY workflow_conversation_history_timestamp_index (timestamp)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ workflow_conversation_history table created<br>";
    } else {
        echo "ℹ️ workflow_conversation_history table already exists<br>";
    }
    
    // Create workflow_machine_configs table
    if (!Schema::hasTable('workflow_machine_configs')) {
        DB::statement("
            CREATE TABLE workflow_machine_configs (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                machine_type enum('ai','function','datatable','template','visualization') NOT NULL,
                config_name varchar(255) NOT NULL,
                config_data json NOT NULL,
                is_active tinyint(1) NOT NULL DEFAULT 1,
                created_by bigint(20) unsigned DEFAULT NULL,
                description text DEFAULT NULL,
                version varchar(20) NOT NULL DEFAULT '1.0',
                created_at timestamp NULL DEFAULT NULL,
                updated_at timestamp NULL DEFAULT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY workflow_machine_configs_machine_config_unique (machine_type,config_name),
                KEY workflow_machine_configs_machine_type_index (machine_type),
                KEY workflow_machine_configs_is_active_index (is_active)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        echo "✅ workflow_machine_configs table created<br>";
    } else {
        echo "ℹ️ workflow_machine_configs table already exists<br>";
    }
    
    // Insert default configurations
    $defaultConfigs = [
        [
            'machine_type' => 'ai',
            'config_name' => 'default_gpt4',
            'config_data' => json_encode([
                'model' => 'gpt-4',
                'max_tokens' => 1000,
                'temperature' => 0.7,
                'system_prompt' => 'You are a helpful WhatsApp assistant for a medical platform.'
            ]),
            'description' => 'Default GPT-4 configuration for AI responses',
            'version' => '1.0',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'machine_type' => 'function',
            'config_name' => 'user_lookup',
            'config_data' => json_encode([
                'function_name' => 'lookupUser',
                'parameters' => ['phone_number'],
                'timeout' => 5000
            ]),
            'description' => 'Function to lookup user information by phone number',
            'version' => '1.0',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'machine_type' => 'datatable',
            'config_name' => 'user_format',
            'config_data' => json_encode([
                'table_format' => 'json',
                'columns' => ['name', 'phone', 'status'],
                'max_rows' => 100
            ]),
            'description' => 'Standard user data table formatting',
            'version' => '1.0',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'machine_type' => 'template',
            'config_name' => 'welcome_message',
            'config_data' => json_encode([
                'template_name' => 'welcome',
                'parameters' => ['user_name'],
                'language' => 'en'
            ]),
            'description' => 'Welcome message template configuration',
            'version' => '1.0',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'machine_type' => 'visualization',
            'config_name' => 'conversation_flow',
            'config_data' => json_encode([
                'chart_type' => 'flowchart',
                'data_source' => 'conversation_history',
                'refresh_interval' => 30
            ]),
            'description' => 'Real-time conversation flow visualization',
            'version' => '1.0',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    foreach ($defaultConfigs as $config) {
        $exists = DB::table('workflow_machine_configs')
            ->where('machine_type', $config['machine_type'])
            ->where('config_name', $config['config_name'])
            ->exists();
            
        if (!$exists) {
            DB::table('workflow_machine_configs')->insert($config);
            echo "✅ Inserted {$config['machine_type']} config: {$config['config_name']}<br>";
        } else {
            echo "ℹ️ Config already exists: {$config['machine_type']}.{$config['config_name']}<br>";
        }
    }
    
    echo "<br><h3 style='color: green;'>🎉 All WhatsApp Workflow tables created successfully!</h3>";
    echo "<p><strong>You can now access:</strong></p>";
    echo "<ul>";
    echo "<li><a href='/admin/whatsapp/workflows'>Scenario Workflows</a></li>";
    echo "<li><a href='/admin/whatsapp/machines'>Machine Configs</a></li>";
    echo "<li><a href='/admin/whatsapp/conversations'>Live Conversations</a></li>";
    echo "<li><a href='/admin/whatsapp/analytics'>Analytics & Reports</a></li>";
    echo "<li><a href='/admin/whatsapp/automation'>Automation Rules</a></li>";
    echo "<li><a href='/admin/whatsapp/settings'>Bot Settings</a></li>";
    echo "</ul>";
    echo "<p style='color: blue;'>🚀 <strong>Your WhatsApp Manager Bot is ready!</strong></p>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Error creating tables:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<p><strong>Troubleshooting:</strong></p>";
    echo "<ul>";
    echo "<li>Check that your database connection in .env is correct</li>";
    echo "<li>Ensure XAMPP MySQL is running</li>";
    echo "<li>Verify the 'freedoctor' database exists</li>";
    echo "</ul>";
}
?>
