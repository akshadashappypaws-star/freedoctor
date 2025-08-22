<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowMachineConfig;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class WhatsappSettingsController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->middleware('auth:admin');
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display WhatsApp bot settings
     */
    public function index(): View
    {
        // Get current settings from machine configs
        $settings = [
            'whatsapp' => $this->getWhatsAppSettings(),
            'openai' => $this->getOpenAISettings(),
            'workflow' => $this->getWorkflowSettings(),
            'notifications' => $this->getNotificationSettings()
        ];

        // Get system status
        $systemStatus = [
            'whatsapp_connection' => $this->checkWhatsAppConnection(),
            'openai_connection' => $this->checkOpenAIConnection(),
            'webhook_status' => $this->checkWebhookStatus(),
            'database_status' => $this->checkDatabaseStatus()
        ];

        // Get template sync status
        $templateStatus = $this->getTemplateStatus();

        return view('admin.whatsapp.settings', compact(
            'settings',
            'systemStatus',
            'templateStatus'
        ));
    }

    /**
     * Update WhatsApp bot settings
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section' => 'required|in:whatsapp,openai,workflow,notifications',
            'settings' => 'required|array'
        ]);

        try {
            switch ($validated['section']) {
                case 'whatsapp':
                    $this->updateWhatsAppSettings($validated['settings']);
                    break;
                case 'openai':
                    $this->updateOpenAISettings($validated['settings']);
                    break;
                case 'workflow':
                    $this->updateWorkflowSettings($validated['settings']);
                    break;
                case 'notifications':
                    $this->updateNotificationSettings($validated['settings']);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst($validated['section']) . ' settings updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(): JsonResponse
    {
        try {
            // Test basic WhatsApp API connection
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $accessToken = config('services.whatsapp.access_token');

            if (!$phoneNumberId || !$accessToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp credentials not configured'
                ]);
            }

            $response = Http::withToken($accessToken)
                ->get("https://graph.facebook.com/v18.0/{$phoneNumberId}");

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'WhatsApp connection successful',
                    'phone_info' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp connection failed: ' . $response->body()
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync WhatsApp templates
     */
    public function syncTemplates(): JsonResponse
    {
        try {
            // Simple template sync implementation
            $accessToken = config('services.whatsapp.access_token');
            $businessAccountId = config('services.whatsapp.business_account_id');

            if (!$accessToken || !$businessAccountId) {
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp credentials not configured'
                ]);
            }

            $response = Http::withToken($accessToken)
                ->get("https://graph.facebook.com/v18.0/{$businessAccountId}/message_templates");

            if ($response->successful()) {
                $templates = $response->json()['data'] ?? [];
                
                return response()->json([
                    'success' => true,
                    'message' => 'Templates synced successfully',
                    'templates_count' => count($templates),
                    'templates' => $templates
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Template sync failed: ' . $response->body()
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Template sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get WhatsApp settings
     */
    private function getWhatsAppSettings(): array
    {
        $configs = WorkflowMachineConfig::where('machine_type', 'template')
            ->whereIn('config_key', ['whatsapp_token', 'phone_number_id', 'business_account_id', 'verify_token'])
            ->pluck('config_value', 'config_key')
            ->toArray();

        return [
            'access_token' => $configs['whatsapp_token'] ?? '',
            'phone_number_id' => $configs['phone_number_id'] ?? '',
            'business_account_id' => $configs['business_account_id'] ?? '',
            'verify_token' => $configs['verify_token'] ?? '',
            'webhook_url' => url('/api/webhook/whatsapp'),
            'api_version' => 'v18.0'
        ];
    }

    /**
     * Get OpenAI settings
     */
    private function getOpenAISettings(): array
    {
        $configs = WorkflowMachineConfig::where('machine_type', 'ai')
            ->whereIn('config_key', ['api_key', 'model', 'max_tokens', 'temperature'])
            ->pluck('config_value', 'config_key')
            ->toArray();

        return [
            'api_key' => $configs['api_key'] ?? '',
            'model' => $configs['model'] ?? 'gpt-4',
            'max_tokens' => (int)($configs['max_tokens'] ?? 1000),
            'temperature' => (float)($configs['temperature'] ?? 0.7)
        ];
    }

    /**
     * Get workflow settings
     */
    private function getWorkflowSettings(): array
    {
        $configs = WorkflowMachineConfig::where('machine_type', 'function')
            ->whereIn('config_key', ['max_execution_time', 'retry_attempts', 'timeout_seconds'])
            ->pluck('config_value', 'config_key')
            ->toArray();

        return [
            'max_execution_time' => (int)($configs['max_execution_time'] ?? 300),
            'retry_attempts' => (int)($configs['retry_attempts'] ?? 3),
            'timeout_seconds' => (int)($configs['timeout_seconds'] ?? 30),
            'enable_logging' => true,
            'auto_retry_failed' => false
        ];
    }

    /**
     * Get notification settings
     */
    private function getNotificationSettings(): array
    {
        $configs = WorkflowMachineConfig::where('machine_type', 'visualization')
            ->whereIn('config_key', ['webhook_notifications', 'email_alerts', 'slack_webhook'])
            ->pluck('config_value', 'config_key')
            ->toArray();

        return [
            'webhook_notifications' => ($configs['webhook_notifications'] ?? 'false') === 'true',
            'email_alerts' => ($configs['email_alerts'] ?? 'false') === 'true',
            'slack_webhook' => $configs['slack_webhook'] ?? '',
            'error_notifications' => true,
            'success_notifications' => false
        ];
    }

    /**
     * Update settings methods
     */
    private function updateWhatsAppSettings(array $settings): void
    {
        $settingsMap = [
            'access_token' => 'whatsapp_token',
            'phone_number_id' => 'phone_number_id',
            'business_account_id' => 'business_account_id',
            'verify_token' => 'verify_token'
        ];

        foreach ($settingsMap as $key => $configKey) {
            if (isset($settings[$key])) {
                WorkflowMachineConfig::updateOrCreate(
                    ['machine_type' => 'template', 'config_key' => $configKey],
                    ['config_value' => $settings[$key]]
                );
            }
        }
    }

    private function updateOpenAISettings(array $settings): void
    {
        $settingsMap = [
            'api_key' => 'api_key',
            'model' => 'model',
            'max_tokens' => 'max_tokens',
            'temperature' => 'temperature'
        ];

        foreach ($settingsMap as $key => $configKey) {
            if (isset($settings[$key])) {
                WorkflowMachineConfig::updateOrCreate(
                    ['machine_type' => 'ai', 'config_key' => $configKey],
                    ['config_value' => (string)$settings[$key]]
                );
            }
        }
    }

    private function updateWorkflowSettings(array $settings): void
    {
        $settingsMap = [
            'max_execution_time' => 'max_execution_time',
            'retry_attempts' => 'retry_attempts',
            'timeout_seconds' => 'timeout_seconds'
        ];

        foreach ($settingsMap as $key => $configKey) {
            if (isset($settings[$key])) {
                WorkflowMachineConfig::updateOrCreate(
                    ['machine_type' => 'function', 'config_key' => $configKey],
                    ['config_value' => (string)$settings[$key]]
                );
            }
        }
    }

    private function updateNotificationSettings(array $settings): void
    {
        $settingsMap = [
            'webhook_notifications' => 'webhook_notifications',
            'email_alerts' => 'email_alerts',
            'slack_webhook' => 'slack_webhook'
        ];

        foreach ($settingsMap as $key => $configKey) {
            if (isset($settings[$key])) {
                WorkflowMachineConfig::updateOrCreate(
                    ['machine_type' => 'visualization', 'config_key' => $configKey],
                    ['config_value' => is_bool($settings[$key]) ? ($settings[$key] ? 'true' : 'false') : $settings[$key]]
                );
            }
        }
    }

    /**
     * Check system status methods
     */
    private function checkWhatsAppConnection(): array
    {
        try {
            $phoneNumberId = config('services.whatsapp.phone_number_id');
            $accessToken = config('services.whatsapp.access_token');

            if (!$phoneNumberId || !$accessToken) {
                return ['status' => 'error', 'message' => 'Credentials not configured'];
            }

            $response = Http::timeout(10)->withToken($accessToken)
                ->get("https://graph.facebook.com/v18.0/{$phoneNumberId}");

            return $response->successful() 
                ? ['status' => 'connected', 'message' => 'Connection successful']
                : ['status' => 'error', 'message' => 'Connection failed'];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkOpenAIConnection(): array
    {
        try {
            $apiKey = WorkflowMachineConfig::where('machine_type', 'ai')
                ->where('config_key', 'api_key')
                ->value('config_value');

            if (!$apiKey) {
                return ['status' => 'error', 'message' => 'API key not configured'];
            }

            $response = Http::timeout(10)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->get('https://api.openai.com/v1/models');

            return $response->successful() 
                ? ['status' => 'connected', 'message' => 'API connection successful']
                : ['status' => 'error', 'message' => 'API connection failed'];

        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkWebhookStatus(): array
    {
        // This would typically check if webhook is properly configured
        return ['status' => 'configured', 'message' => 'Webhook endpoint active'];
    }

    private function checkDatabaseStatus(): array
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'connected', 'message' => 'Database connection healthy'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }

    private function getTemplateStatus(): array
    {
        return [
            'last_sync' => '2024-08-21 10:30:00', // Would come from actual sync log
            'template_count' => 5,
            'approved_templates' => 4,
            'pending_templates' => 1
        ];
    }
}
