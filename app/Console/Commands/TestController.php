<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\WhatsappConversationController;

class TestController extends Command
{
    protected $signature = 'test:controller';
    protected $description = 'Test controller methods';

    public function handle()
    {
        try {
            $this->info('Testing WhatsappConversationController...');
            
            // Check if class exists
            $this->info('Class exists: ' . (class_exists(WhatsappConversationController::class) ? 'YES' : 'NO'));
            
            // Get methods
            $methods = get_class_methods(WhatsappConversationController::class);
            $this->info('Available methods: ' . implode(', ', $methods));
            
            // Check specifically for index method
            $this->info('Index method exists: ' . (method_exists(WhatsappConversationController::class, 'index') ? 'YES' : 'NO'));
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
