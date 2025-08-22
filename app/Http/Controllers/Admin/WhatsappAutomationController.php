<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowMachineConfig;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WhatsappAutomationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display automation rules dashboard
     */
    public function index(): View
    {
        // Get automation rules from machine configs
        $automationRules = WorkflowMachineConfig::where('config_key', 'like', 'automation_%')
            ->orWhere('config_key', 'like', 'trigger_%')
            ->orWhere('config_key', 'like', 'rule_%')
            ->get()
            ->groupBy('machine_type');

        // Predefined automation rule templates
        $ruleTemplates = [
            'greeting_response' => [
                'name' => 'Auto Greeting Response',
                'description' => 'Automatically respond to greetings like "Hi", "Hello", "Good morning"',
                'trigger_type' => 'keyword_match',
                'triggers' => ['hi', 'hello', 'good morning', 'good afternoon', 'good evening'],
                'action' => 'send_template',
                'response' => 'Hello! 👋 Welcome to FreeDoctor. How can I help you today?',
                'machine' => 'template'
            ],
            'working_hours' => [
                'name' => 'Working Hours Auto-Response',
                'description' => 'Send automated response outside working hours',
                'trigger_type' => 'time_based',
                'conditions' => ['outside_hours' => '09:00-18:00'],
                'action' => 'send_template',
                'response' => 'Thank you for contacting FreeDoctor! Our support team is currently offline. We operate from 9 AM to 6 PM. Your message is important to us and we\'ll respond as soon as possible.',
                'machine' => 'template'
            ],
            'emergency_keywords' => [
                'name' => 'Emergency Keyword Detection',
                'description' => 'Detect emergency keywords and prioritize response',
                'trigger_type' => 'keyword_match',
                'triggers' => ['emergency', 'urgent', 'help', 'critical', 'pain'],
                'action' => 'priority_escalation',
                'response' => '🚨 Emergency detected. Connecting you to immediate assistance...',
                'machine' => 'ai'
            ],
            'appointment_followup' => [
                'name' => 'Appointment Follow-up',
                'description' => 'Automatic follow-up messages after appointments',
                'trigger_type' => 'scheduled',
                'conditions' => ['after_appointment' => '24_hours'],
                'action' => 'send_followup',
                'response' => 'Hi! How was your appointment yesterday? We\'d love to hear your feedback.',
                'machine' => 'template'
            ],
            'incomplete_booking' => [
                'name' => 'Incomplete Booking Reminder',
                'description' => 'Remind users who started but didn\'t complete booking',
                'trigger_type' => 'workflow_incomplete',
                'conditions' => ['scenario' => 'book_appointment', 'timeout' => '30_minutes'],
                'action' => 'send_reminder',
                'response' => 'It looks like you were booking an appointment. Would you like to continue?',
                'machine' => 'function'
            ]
        ];

        // Get active automation statistics
        $statistics = [
            'total_rules' => count($ruleTemplates),
            'active_rules' => $automationRules->flatten()->where('config_value', 'true')->count(),
            'triggered_today' => 0, // Would come from logs
            'success_rate' => 95.5 // Would come from performance metrics
        ];

        return view('admin.whatsapp.automation', compact(
            'automationRules',
            'ruleTemplates',
            'statistics'
        ));
    }

    /**
     * Store new automation rule
     */
    public function storeRule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'trigger_type' => 'required|in:keyword_match,time_based,scheduled,workflow_incomplete',
            'triggers' => 'required|array',
            'conditions' => 'sometimes|array',
            'action' => 'required|string',
            'response' => 'required|string|max:1000',
            'machine' => 'required|in:ai,function,template,datatable,visualization',
            'is_active' => 'boolean'
        ]);

        try {
            // Create automation rule as machine config
            $ruleKey = 'automation_rule_' . time();
            
            WorkflowMachineConfig::create([
                'machine_type' => $validated['machine'],
                'config_key' => $ruleKey,
                'config_value' => json_encode([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'trigger_type' => $validated['trigger_type'],
                    'triggers' => $validated['triggers'],
                    'conditions' => $validated['conditions'] ?? [],
                    'action' => $validated['action'],
                    'response' => $validated['response'],
                    'is_active' => $validated['is_active'] ?? true,
                    'created_at' => now()->toISOString()
                ]),
                'description' => 'Automation Rule: ' . $validated['name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Automation rule created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create automation rule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update automation rule
     */
    public function updateRule(Request $request, int $ruleId): JsonResponse
    {
        $rule = WorkflowMachineConfig::findOrFail($ruleId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'trigger_type' => 'required|in:keyword_match,time_based,scheduled,workflow_incomplete',
            'triggers' => 'required|array',
            'conditions' => 'sometimes|array',
            'action' => 'required|string',
            'response' => 'required|string|max:1000',
            'is_active' => 'boolean'
        ]);

        try {
            $currentConfig = json_decode($rule->config_value, true);
            
            $updatedConfig = array_merge($currentConfig, [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'trigger_type' => $validated['trigger_type'],
                'triggers' => $validated['triggers'],
                'conditions' => $validated['conditions'] ?? [],
                'action' => $validated['action'],
                'response' => $validated['response'],
                'is_active' => $validated['is_active'] ?? true,
                'updated_at' => now()->toISOString()
            ]);

            $rule->update([
                'config_value' => json_encode($updatedConfig),
                'description' => 'Automation Rule: ' . $validated['name']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Automation rule updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update automation rule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete automation rule
     */
    public function deleteRule(int $ruleId): JsonResponse
    {
        try {
            $rule = WorkflowMachineConfig::findOrFail($ruleId);
            $rule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Automation rule deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete automation rule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle automation rule active status
     */
    public function toggleRule(int $ruleId): JsonResponse
    {
        try {
            $rule = WorkflowMachineConfig::findOrFail($ruleId);
            $currentConfig = json_decode($rule->config_value, true);
            
            $currentConfig['is_active'] = !($currentConfig['is_active'] ?? true);
            $currentConfig['updated_at'] = now()->toISOString();

            $rule->update([
                'config_value' => json_encode($currentConfig)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Automation rule ' . ($currentConfig['is_active'] ? 'activated' : 'deactivated'),
                'is_active' => $currentConfig['is_active']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle automation rule: ' . $e->getMessage()
            ], 500);
        }
    }
}
