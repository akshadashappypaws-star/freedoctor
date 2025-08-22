<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkflowMachineConfig;

class WorkflowMachineConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // AI Machine Configurations
        WorkflowMachineConfig::setConfig('ai', 'openai_settings', [
            'model' => 'gpt-4',
            'temperature' => 0.7,
            'max_tokens' => 1500,
            'timeout' => 30
        ], 100);

        WorkflowMachineConfig::setConfig('ai', 'intent_patterns', [
            'find_doctor' => ['doctor', 'physician', 'specialist', 'appointment', 'consultation'],
            'find_health_camp' => ['camp', 'health camp', 'medical camp', 'free checkup'],
            'book_appointment' => ['book', 'schedule', 'appointment', 'meeting'],
            'payment_inquiry' => ['payment', 'pay', 'cost', 'fee', 'price'],
            'medical_question' => ['symptoms', 'pain', 'health', 'medical', 'treatment'],
            'location_query' => ['nearby', 'near me', 'location', 'address', 'distance']
        ], 90);

        // Function Machine Configurations
        WorkflowMachineConfig::setConfig('function', 'search_defaults', [
            'default_radius_km' => 10,
            'max_results' => 20,
            'timeout_seconds' => 10
        ], 100);

        WorkflowMachineConfig::setConfig('function', 'database_connections', [
            'max_retries' => 3,
            'retry_delay_ms' => 1000,
            'connection_timeout' => 30
        ], 90);

        // DataTable Machine Configurations
        WorkflowMachineConfig::setConfig('datatable', 'formatting_options', [
            'max_whatsapp_message_length' => 1600,
            'max_items_in_list' => 5,
            'include_distance' => true,
            'include_rating' => true
        ], 100);

        WorkflowMachineConfig::setConfig('datatable', 'currency_settings', [
            'currency_symbol' => '₹',
            'decimal_places' => 0,
            'thousands_separator' => ','
        ], 90);

        // Template Machine Configurations
        WorkflowMachineConfig::setConfig('template', 'whatsapp_settings', [
            'fallback_to_text' => true,
            'template_timeout' => 15,
            'retry_failed_templates' => true
        ], 100);

        WorkflowMachineConfig::setConfig('template', 'message_templates', [
            'welcome_message' => '🏥 *Welcome to FreeDoctor!*\n\nHi {{user_name}}! 👋\n\nI\'m here to help you find:\n• 👨‍⚕️ Nearby doctors\n• 🏕️ Health camps\n• 📅 Book appointments\n• 💊 Medical information\n\nJust type what you\'re looking for and I\'ll assist you!',
            'error_message' => '😔 Something went wrong while processing your request. Please try again or type \'help\' for assistance.',
            'no_results' => '😔 Sorry, no {{search_type}} found matching your criteria.\n\nTry:\n• Expanding your search radius\n• Different {{search_criteria}}\n• Different location\n\nType \'help\' for more options.'
        ], 80);

        // Visualization Machine Configurations
        WorkflowMachineConfig::setConfig('visualization', 'websocket_settings', [
            'broadcast_enabled' => true,
            'broadcast_channels' => [
                'admin.dashboard',
                'admin.workflow-monitor',
                'admin.notifications'
            ],
            'retry_failed_broadcasts' => true
        ], 100);

        WorkflowMachineConfig::setConfig('visualization', 'performance_tracking', [
            'track_response_time' => true,
            'track_success_rate' => true,
            'track_user_satisfaction' => true,
            'metric_retention_days' => 30
        ], 90);

        // General workflow configurations
        WorkflowMachineConfig::setConfig('general', 'workflow_limits', [
            'max_steps_per_workflow' => 20,
            'max_execution_time_minutes' => 5,
            'max_retries_per_step' => 3
        ], 100);

        WorkflowMachineConfig::setConfig('general', 'error_handling', [
            'auto_retry_recoverable_errors' => true,
            'send_error_notifications' => true,
            'fallback_to_human_agent' => false
        ], 90);
    }
}
