<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class SetupWorkflowSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'workflow:setup {--force : Force setup even if tables exist}';

    /**
     * The console command description.
     */
    protected $description = 'Set up the WhatsApp Consultant Agent Bot workflow system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up WhatsApp Consultant Agent Bot Workflow System...');
        $this->newLine();

        try {
            // Check if force flag is provided
            $force = $this->option('force');

            if ($force) {
                $this->warn('⚠️  Force flag detected. This will drop and recreate workflow tables.');
                if (!$this->confirm('Are you sure you want to continue?')) {
                    $this->info('Setup cancelled.');
                    return 0;
                }
            }

            // Step 1: Run migrations
            $this->info('📊 Running workflow migrations...');
            if ($force) {
                Artisan::call('migrate:fresh', ['--path' => 'database/migrations/2025_08_21_000001_create_workflows_table.php']);
            } else {
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_08_21_000001_create_workflows_table.php']);
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_08_21_000002_create_workflow_logs_table.php']);
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_08_21_000003_create_workflow_errors_table.php']);
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_08_21_000004_create_workflow_conversation_history_table.php']);
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_08_21_000005_create_workflow_machine_configs_table.php']);
                Artisan::call('migrate', ['--path' => 'database/migrations/2025_08_21_000006_create_workflow_performance_metrics_table.php']);
            }
            $this->info('✅ Migrations completed successfully!');

            // Step 2: Seed machine configurations
            $this->info('⚙️  Seeding workflow machine configurations...');
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\WorkflowMachineConfigSeeder']);
            $this->info('✅ Machine configurations seeded successfully!');

            // Step 3: Verify setup
            $this->info('🔍 Verifying setup...');
            $this->verifySetup();

            // Step 4: Display next steps
            $this->displayNextSteps();

            $this->newLine();
            $this->info('🎉 WhatsApp Consultant Agent Bot Workflow System setup completed successfully!');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Setup failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Verify the setup
     */
    private function verifySetup(): void
    {
        $checks = [
            'workflows table' => Schema::hasTable('workflows'),
            'workflow_logs table' => Schema::hasTable('workflow_logs'),
            'workflow_errors table' => Schema::hasTable('workflow_errors'),
            'workflow_conversation_history table' => Schema::hasTable('workflow_conversation_history'),
            'workflow_machine_configs table' => Schema::hasTable('workflow_machine_configs'),
            'workflow_performance_metrics table' => Schema::hasTable('workflow_performance_metrics'),
        ];

        foreach ($checks as $name => $exists) {
            if ($exists) {
                $this->info("  ✅ {$name} exists");
            } else {
                $this->error("  ❌ {$name} missing");
            }
        }

        // Check machine configurations
        $configCount = \App\Models\WorkflowMachineConfig::count();
        if ($configCount > 0) {
            $this->info("  ✅ {$configCount} machine configurations loaded");
        } else {
            $this->error("  ❌ No machine configurations found");
        }
    }

    /**
     * Display next steps
     */
    private function displayNextSteps(): void
    {
        $this->newLine();
        $this->info('📋 Next Steps:');
        $this->line('');
        
        $this->line('1. 🔧 Configure WhatsApp API in your .env file:');
        $this->line('   WHATSAPP_ACCESS_TOKEN=your_access_token');
        $this->line('   WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id');
        $this->line('   WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id');
        $this->line('   WHATSAPP_VERIFY_TOKEN=your_verify_token');
        $this->line('   WHATSAPP_APP_SECRET=your_app_secret');
        $this->line('');

        $this->line('2. 🤖 Configure OpenAI API in your .env file:');
        $this->line('   OPENAI_API_KEY=your_openai_api_key');
        $this->line('');

        $this->line('3. 🌐 Set up WhatsApp webhook:');
        $this->line('   URL: ' . url('/api/webhook/whatsapp'));
        $this->line('   Verify Token: Use the value from WHATSAPP_VERIFY_TOKEN');
        $this->line('');

        $this->line('4. 🧪 Test the webhook:');
        $this->line('   POST ' . url('/api/webhook/whatsapp/test'));
        $this->line('');

        $this->line('5. 📊 Access workflow dashboard:');
        $this->line('   Admin Dashboard: ' . url('/admin/workflows'));
        $this->line('   API Endpoints: ' . url('/api/workflows'));
        $this->line('');

        $this->line('6. 🎯 Sample WhatsApp messages to test:');
        $this->line('   • "Find doctors near me"');
        $this->line('   • "Show health camps in Delhi"');
        $this->line('   • "Book appointment with cardiologist"');
        $this->line('   • "What are the symptoms of diabetes?"');
        $this->line('');

        $this->warn('⚠️  Important: Make sure to configure your WhatsApp Business API webhook URL in the Meta Business dashboard.');
    }
}
