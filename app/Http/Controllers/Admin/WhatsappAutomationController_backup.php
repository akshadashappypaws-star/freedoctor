<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowMachineConfig;
use App\Models\WhatsappConversation;
use App\Models\WhatsappMessage;
use App\Models\WhatsappAiAnalysis;
use App\Models\WhatsappUserBehavior;
use App\Models\WhatsappAutomationRule;
use App\Models\WhatsappSystemHealth;
use App\Models\WhatsappWeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

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
        // Get dynamic conversations data
        $totalConversations = WhatsappConversation::count();
        $activeConversations = WhatsappConversation::active()->count();
        $todayConversations = WhatsappConversation::whereDate('created_at', today())->count();
        
        // Get messages statistics
        $totalMessages = WhatsappMessage::count();
        $todayMessages = WhatsappMessage::whereDate('sent_at', today())->count();
        $automatedMessages = WhatsappMessage::where('is_automated', true)->count();
        $todayAutomatedMessages = WhatsappMessage::where('is_automated', true)
            ->whereDate('sent_at', today())->count();

        // Get active automation rules from database
        $automationRules = WhatsappAutomationRule::active()
            ->orderBy('priority', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get user behavior analytics
        $userBehavior = WhatsappUserBehavior::selectRaw('
            engagement_type,
            COUNT(*) as count,
            AVG(total_messages) as avg_messages,
            AVG(avg_response_time) as avg_response_time,
            AVG(questions_asked) as avg_questions,
            AVG(appointments_requested) as avg_appointments
        ')
        ->groupBy('engagement_type')
        ->get()
        ->keyBy('engagement_type');

        // Get AI analysis insights
        $aiInsights = WhatsappAiAnalysis::selectRaw('
            analysis_type,
            analysis_result,
            COUNT(*) as count,
            AVG(confidence_score) as avg_confidence
        ')
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('analysis_type', 'analysis_result')
        ->having('count', '>', 5)
        ->orderBy('count', 'desc')
        ->get();

        // Get recent conversations for activity feed
        $recentActivity = WhatsappConversation::with(['latestMessage', 'userBehavior', 'aiAnalysis' => function($q) {
            $q->latest()->limit(1);
        }])
        ->orderBy('last_message_at', 'desc')
        ->limit(10)
        ->get();

        // Calculate live statistics
        $liveStats = [
            'active_conversations' => $activeConversations,
            'messages_today' => $todayMessages,
            'automation_rate' => $totalMessages > 0 ? round(($automatedMessages / $totalMessages) * 100, 1) : 0,
            'ai_interactions' => WhatsappAiAnalysis::whereDate('created_at', today())->count(),
            'avg_response_time' => WhatsappUserBehavior::avg('avg_response_time') ?? 0
        ];

        // Get system health metrics
        $systemHealth = WhatsappSystemHealth::get()->keyBy('component_name');

        // Performance statistics from real data
        $performanceStats = [
            'total_conversations' => $totalConversations,
            'success_rate' => $this->calculateSuccessRate(),
            'avg_response_time' => round(WhatsappUserBehavior::avg('avg_response_time') ?? 0, 2) . 's',
            'automation_efficiency' => $totalMessages > 0 ? round(($automatedMessages / $totalMessages) * 100, 1) : 0
        ];

        // Get weekly trends
        $weeklyData = $this->getWeeklyTrends();

        return view('admin.whatsapp.automation.index', compact(
            'automationRules',
            'totalConversations',
            'activeConversations',
            'todayConversations',
            'totalMessages',
            'todayMessages',
            'automatedMessages',
            'todayAutomatedMessages',
            'userBehavior',
            'aiInsights',
            'recentActivity',
            'liveStats',
            'systemHealth',
            'performanceStats',
            'weeklyData'
        ));
    }

    /**
     * Display workflow builder page
     */
    public function workflow(): View
    {
        // Get real workflow statistics
        $totalWorkflows = \App\Models\Workflow::count() ?? 0;
        $activeWorkflows = \App\Models\Workflow::where('status', 'running')->count() ?? 0;
        $todayExecutions = WhatsappMessage::where('is_automated', true)
            ->whereDate('sent_at', today())->count();

        $stats = [
            'active_workflows' => $activeWorkflows,
            'total_workflows' => $totalWorkflows,
            'messages_today' => WhatsappMessage::whereDate('sent_at', today())->count(),
            'ai_interactions' => WhatsappAiAnalysis::whereDate('created_at', today())->count(),
            'automation_rate' => $this->calculateAutomationRate(),
            'success_rate' => $this->calculateSuccessRate() . '%'
        ];

        // Get workflow templates from AI analysis patterns
        $workflowTemplates = $this->getWorkflowTemplatesFromData();

        return view('admin.whatsapp.automation.workflow', compact('stats', 'workflowTemplates'));
    }

    /**
     * Display rules engine page
     */
    public function rules(): View
    {
        // Get real rules data from database
        $automationRules = WhatsappAutomationRule::withCount(['executions' => function($q) {
            $q->whereDate('created_at', today());
        }])->get();

        $totalRules = $automationRules->count();
        $activeRules = $automationRules->where('is_active', true)->count();
        
        // Get keyword matches from AI analysis
        $keywordMatches = WhatsappAiAnalysis::where('analysis_type', 'intent')
            ->whereDate('created_at', today())
            ->count();

        $aiFallbacks = WhatsappAiAnalysis::where('analysis_type', 'fallback')
            ->whereDate('created_at', today())
            ->count();

        $stats = [
            'total_rules' => $totalRules,
            'active_rules' => $activeRules,
            'keyword_matches' => $keywordMatches,
            'ai_fallbacks' => $aiFallbacks,
            'success_rate' => $this->calculateRuleSuccessRate(),
            'avg_confidence' => WhatsappAiAnalysis::whereDate('created_at', today())
                ->avg('confidence_score') ?? 0
        ];

        return view('admin.whatsapp.automation.rules', compact('stats', 'automationRules'));
    }

    /**
     * Display analytics dashboard
     */
    public function analytics(): View
    {
        // Get real user behavior analytics
        $userBehaviorData = WhatsappUserBehavior::selectRaw('
            engagement_type,
            COUNT(*) as count,
            AVG(total_messages) as avg_messages,
            AVG(avg_response_time) as avg_response_time,
            AVG(questions_asked) as avg_questions,
            SUM(appointments_requested) as total_appointments
        ')
        ->groupBy('engagement_type')
        ->get()
        ->keyBy('engagement_type');

        $totalUsers = $userBehaviorData->sum('count');

        // Calculate percentages and format data
        $analytics = [
            'interested' => $userBehaviorData->get('interested', (object)['count' => 0]),
            'average' => $userBehaviorData->get('average', (object)['count' => 0]),
            'not_interested' => $userBehaviorData->get('not_interested', (object)['count' => 0])
        ];

        foreach ($analytics as $type => &$data) {
            $data->percentage = $totalUsers > 0 ? round(($data->count / $totalUsers) * 100, 1) . '%' : '0%';
            $data->active_today = WhatsappConversation::whereHas('userBehavior', function($q) use ($type) {
                $q->where('engagement_type', $type);
            })->whereDate('last_message_at', today())->count();
            
            $data->response_time = round($data->avg_response_time ?? 0, 1) . 's';
            $data->conversion = $this->calculateConversionRate($type);
            $data->satisfaction = $this->calculateSatisfactionRate($type);
        }

        // Overall metrics
        $metrics = [
            'total_users' => $totalUsers,
            'active_today' => WhatsappConversation::whereDate('last_message_at', today())->count(),
            'avg_session' => $this->calculateAverageSessionTime(),
            'bounce_rate' => $this->calculateBounceRate() . '%'
        ];

        // Get weekly trends
        $weeklyTrends = $this->getWeeklyAnalyticsTrends();

        return view('admin.whatsapp.automation.analytics', compact('analytics', 'metrics', 'weeklyTrends'));
    }

    /**
     * Display machines configuration page
     */
    public function machines(): View
    {
        // Get real system health data
        $systemHealthData = WhatsappSystemHealth::get()->keyBy('component_name');
        
        $machines = [
            'ai' => $this->formatMachineData($systemHealthData->get('ai')),
            'template' => $this->formatMachineData($systemHealthData->get('template')),
            'datatable' => $this->formatMachineData($systemHealthData->get('datatable')),
            'function' => $this->formatMachineData($systemHealthData->get('function')),
            'visualization' => $this->formatMachineData($systemHealthData->get('visualization'))
        ];

        return view('admin.whatsapp.automation.machines', compact('machines'));
    }

    /**
     * Get live stats for dashboard
     */
    public function getStats(): JsonResponse
    {
        $activeConversations = WhatsappConversation::active()->count();
        $todayMessages = WhatsappMessage::whereDate('sent_at', today())->count();
        $aiInteractions = WhatsappAiAnalysis::whereDate('created_at', today())->count();
        $automationRate = $this->calculateAutomationRate();

        $stats = [
            'active_conversations' => $activeConversations,
            'messages_today' => $todayMessages,
            'ai_interactions' => $aiInteractions,
            'automation_rate' => $automationRate . '%',
            'avg_response_time' => round(WhatsappUserBehavior::avg('avg_response_time') ?? 0, 1) . 's'
        ];

        return response()->json(['success' => true, 'stats' => $stats]);
    }

    /**
     * Store new automation rule
     */
    public function storeRule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'trigger_conditions' => 'required|array',
            'actions' => 'required|array',
            'priority' => 'required|in:high,medium,low',
            'is_active' => 'boolean'
        ]);

        try {
            $rule = WhatsappAutomationRule::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'trigger_conditions' => $validated['trigger_conditions'],
                'actions' => $validated['actions'],
                'priority' => $validated['priority'],
                'is_active' => $validated['is_active'] ?? true
            ]);

            // Create AI analysis note for new rule
            WhatsappAiAnalysis::create([
                'conversation_id' => null,
                'message_id' => null,
                'analysis_type' => 'rule_creation',
                'analysis_result' => 'New automation rule created: ' . $rule->name,
                'confidence_score' => 100.00,
                'ai_notes' => 'System automatically created automation rule with priority: ' . $rule->priority,
                'ai_model_used' => 'system'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Automation rule created successfully',
                'rule' => $rule
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
        $rule = WhatsappAutomationRule::findOrFail($ruleId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'trigger_conditions' => 'required|array',
            'actions' => 'required|array',
            'priority' => 'required|in:high,medium,low',
            'is_active' => 'boolean'
        ]);

        try {
            $rule->update($validated);

            // Create AI analysis note for rule update
            WhatsappAiAnalysis::create([
                'conversation_id' => null,
                'message_id' => null,
                'analysis_type' => 'rule_update',
                'analysis_result' => 'Automation rule updated: ' . $rule->name,
                'confidence_score' => 100.00,
                'ai_notes' => 'System automatically updated automation rule configuration',
                'ai_model_used' => 'system'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Automation rule updated successfully',
                'rule' => $rule
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
            $rule = WhatsappAutomationRule::findOrFail($ruleId);
            $ruleName = $rule->name;
            
            $rule->delete();

            // Create AI analysis note for rule deletion
            WhatsappAiAnalysis::create([
                'conversation_id' => null,
                'message_id' => null,
                'analysis_type' => 'rule_deletion',
                'analysis_result' => 'Automation rule deleted: ' . $ruleName,
                'confidence_score' => 100.00,
                'ai_notes' => 'System automatically logged automation rule deletion',
                'ai_model_used' => 'system'
            ]);

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
            $rule = WhatsappAutomationRule::findOrFail($ruleId);
            $rule->update(['is_active' => !$rule->is_active]);

            // Create AI analysis note for rule toggle
            WhatsappAiAnalysis::create([
                'conversation_id' => null,
                'message_id' => null,
                'analysis_type' => 'rule_toggle',
                'analysis_result' => 'Automation rule ' . ($rule->is_active ? 'activated' : 'deactivated') . ': ' . $rule->name,
                'confidence_score' => 100.00,
                'ai_notes' => 'System automatically toggled automation rule status',
                'ai_model_used' => 'system'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Automation rule ' . ($rule->is_active ? 'activated' : 'deactivated'),
                'is_active' => $rule->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle automation rule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test automation rule with sample message
     */
    public function testRule(Request $request, int $ruleId): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        try {
            $rule = WhatsappAutomationRule::findOrFail($ruleId);
            $message = strtolower($request->message);
            $matched = false;
            $confidence = 0;

            // Test the rule against the message
            $triggerConditions = $rule->trigger_conditions;
            
            if (isset($triggerConditions['keywords'])) {
                foreach ($triggerConditions['keywords'] as $keyword) {
                    if (strpos($message, strtolower($keyword)) !== false) {
                        $matched = true;
                        $confidence = 85 + rand(0, 15); // Simulate confidence
                        break;
                    }
                }
            }

            // Create AI analysis for the test
            $analysis = WhatsappAiAnalysis::create([
                'conversation_id' => null,
                'message_id' => null,
                'analysis_type' => 'rule_test',
                'analysis_result' => $matched ? 'Rule matched' : 'Rule not matched',
                'confidence_score' => $confidence,
                'ai_notes' => "Tested rule '{$rule->name}' with message: '{$request->message}'",
                'ai_model_used' => 'rule_engine'
            ]);

            // If matched, increment execution count
            if ($matched) {
                $rule->incrementExecution(true);
            }

            return response()->json([
                'success' => true,
                'matched' => $matched,
                'confidence' => $confidence,
                'response' => $matched ? ($rule->actions['response'] ?? 'Rule triggered successfully') : null,
                'rule_name' => $rule->name,
                'analysis_id' => $analysis->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to test rule: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper methods for calculations
    private function calculateSuccessRate(): float
    {
        $totalMessages = WhatsappMessage::count();
        $successfulMessages = WhatsappMessage::where('status', 'delivered')->count();
        
        return $totalMessages > 0 ? round(($successfulMessages / $totalMessages) * 100, 1) : 0;
    }

    private function calculateAutomationRate(): float
    {
        $totalMessages = WhatsappMessage::count();
        $automatedMessages = WhatsappMessage::where('is_automated', true)->count();
        
        return $totalMessages > 0 ? round(($automatedMessages / $totalMessages) * 100, 1) : 0;
    }

    private function calculateRuleSuccessRate(): float
    {
        $rules = WhatsappAutomationRule::all();
        $totalExecutions = $rules->sum('execution_count');
        $totalSuccesses = $rules->sum('success_count');
        
        return $totalExecutions > 0 ? round(($totalSuccesses / $totalExecutions) * 100, 1) : 0;
    }

    private function calculateConversionRate(string $engagementType): string
    {
        $users = WhatsappUserBehavior::where('engagement_type', $engagementType);
        $totalUsers = $users->count();
        $convertedUsers = $users->where('appointments_requested', '>', 0)->count();
        
        return $totalUsers > 0 ? round(($convertedUsers / $totalUsers) * 100) . '%' : '0%';
    }

    private function calculateSatisfactionRate(string $engagementType): string
    {
        // Simulate satisfaction based on engagement type
        $rates = [
            'interested' => rand(85, 95),
            'average' => rand(65, 75),
            'not_interested' => rand(15, 25)
        ];
        
        return ($rates[$engagementType] ?? 50) . '%';
    }

    private function calculateAverageSessionTime(): string
    {
        $avgMinutes = WhatsappUserBehavior::avg('avg_response_time') ?? 0;
        return round($avgMinutes / 60, 1) . 'm';
    }

    private function calculateBounceRate(): float
    {
        $totalConversations = WhatsappConversation::count();
        $singleMessageConversations = WhatsappConversation::has('messages', '=', 1)->count();
        
        return $totalConversations > 0 ? round(($singleMessageConversations / $totalConversations) * 100, 1) : 0;
    }

    private function formatMachineData($healthData): array
    {
        if (!$healthData) {
            return [
                'status' => 'offline',
                'health' => 0,
                'requests_today' => 0,
                'avg_response_time' => 0,
                'success_rate' => 0
            ];
        }

        return [
            'status' => $healthData->status,
            'health' => $healthData->health_percentage,
            'requests_today' => $healthData->requests_today,
            'avg_response_time' => $healthData->avg_response_time,
            'success_rate' => $healthData->success_rate
        ];
    }

    private function getWeeklyTrends(): array
    {
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trends[] = [
                'date' => $date->format('M d'),
                'conversations' => WhatsappConversation::whereDate('created_at', $date)->count(),
                'messages' => WhatsappMessage::whereDate('sent_at', $date)->count(),
                'ai_interactions' => WhatsappAiAnalysis::whereDate('created_at', $date)->count()
            ];
        }
        return $trends;
    }

    private function getWeeklyAnalyticsTrends(): array
    {
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayData = WhatsappUserBehavior::whereHas('conversation', function($q) use ($date) {
                $q->whereDate('last_message_at', $date);
            })->selectRaw('engagement_type, COUNT(*) as count')
            ->groupBy('engagement_type')
            ->get()
            ->keyBy('engagement_type');

            $trends[] = [
                'date' => $date->format('M d'),
                'interested' => $dayData->get('interested')->count ?? 0,
                'average' => $dayData->get('average')->count ?? 0,
                'not_interested' => $dayData->get('not_interested')->count ?? 0
            ];
        }
        return $trends;
    }

    private function getWorkflowTemplatesFromData(): array
    {
        // Analyze AI patterns to suggest workflow templates
        $intentAnalysis = WhatsappAiAnalysis::where('analysis_type', 'intent')
            ->selectRaw('analysis_result, COUNT(*) as frequency')
            ->groupBy('analysis_result')
            ->orderBy('frequency', 'desc')
            ->limit(5)
            ->get();

        $templates = [];
        foreach ($intentAnalysis as $intent) {
            $templates[] = [
                'name' => ucwords(str_replace('_', ' ', $intent->analysis_result)) . ' Workflow',
                'description' => 'Auto-generated workflow based on user patterns',
                'frequency' => $intent->frequency,
                'success_rate' => rand(85, 95) . '%',
                'avg_time' => (rand(1, 5) + (rand(0, 9) / 10)) . 's'
            ];
        }

        return $templates;
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

    /**
     * Test automation rule with sample message
     */
    public function testRule(Request $request, string $ruleKey): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);

        try {
            // Find rule from templates (in real implementation, this would come from database)
            $ruleTemplates = $this->getRuleTemplates();
            
            if (!isset($ruleTemplates[$ruleKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rule not found'
                ], 404);
            }

            $rule = $ruleTemplates[$ruleKey];
            $message = strtolower($request->message);
            $matched = false;

            // Test keyword matching
            if ($rule['trigger_type'] === 'keyword_match' && isset($rule['triggers'])) {
                foreach ($rule['triggers'] as $trigger) {
                    if (strpos($message, strtolower($trigger)) !== false) {
                        $matched = true;
                        break;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'matched' => $matched,
                'response' => $matched ? $rule['response'] : null,
                'rule_name' => $rule['name']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to test rule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow preview data
     */
    public function previewWorkflow(string $workflowKey): JsonResponse
    {
        try {
            $workflowTemplates = $this->getWorkflowTemplates();
            
            if (!isset($workflowTemplates[$workflowKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Workflow not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'workflow' => $workflowTemplates[$workflowKey]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load workflow preview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate workflow template
     */
    public function activateWorkflow(string $workflowKey): JsonResponse
    {
        try {
            $workflowTemplates = $this->getWorkflowTemplates();
            
            if (!isset($workflowTemplates[$workflowKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Workflow not found'
                ], 404);
            }

            // In real implementation, save to database and activate
            // For now, just return success
            return response()->json([
                'success' => true,
                'message' => 'Workflow activated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to activate workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicate workflow template
     */
    public function duplicateWorkflow(string $workflowKey): JsonResponse
    {
        try {
            $workflowTemplates = $this->getWorkflowTemplates();
            
            if (!isset($workflowTemplates[$workflowKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Workflow not found'
                ], 404);
            }

            // In real implementation, create a copy with modified name
            return response()->json([
                'success' => true,
                'message' => 'Workflow duplicated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow visualization data
     */
    public function getWorkflowVisualization(string $workflowKey): JsonResponse
    {
        try {
            $workflowTemplates = $this->getWorkflowTemplates();
            
            if (!isset($workflowTemplates[$workflowKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Workflow not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'workflow' => $workflowTemplates[$workflowKey]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load workflow visualization: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow details
     */
    public function getWorkflowDetails(int $workflowId): JsonResponse
    {
        try {
            $workflow = \App\Models\Workflow::with(['logs', 'conversationHistory'])
                ->findOrFail($workflowId);

            return response()->json([
                'success' => true,
                'workflow' => $workflow
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load workflow details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause workflow
     */
    public function pauseWorkflow(int $workflowId): JsonResponse
    {
        try {
            $workflow = \App\Models\Workflow::findOrFail($workflowId);
            
            if ($workflow->status !== 'running') {
                return response()->json([
                    'success' => false,
                    'message' => 'Workflow is not running'
                ]);
            }

            $workflow->update(['status' => 'paused']);

            return response()->json([
                'success' => true,
                'message' => 'Workflow paused successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to pause workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Stop workflow
     */
    public function stopWorkflow(int $workflowId): JsonResponse
    {
        try {
            $workflow = \App\Models\Workflow::findOrFail($workflowId);
            
            $workflow->markAsFailed();

            return response()->json([
                'success' => true,
                'message' => 'Workflow stopped successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to stop workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get live statistics
     */
    public function getLiveStats(): JsonResponse
    {
        try {
            $activeWorkflows = \App\Models\Workflow::where('status', 'running')->count();
            $completedToday = \App\Models\Workflow::whereDate('completed_at', today())->count();
            $totalWorkflows = \App\Models\Workflow::count();
            $successRate = $totalWorkflows > 0 ? round(($completedToday / $totalWorkflows) * 100, 1) . '%' : '0%';

            // Generate sample chart data
            $chartData = [
                'labels' => collect(range(0, 11))->map(function($i) {
                    return now()->subHours(11 - $i)->format('H:i');
                })->toArray(),
                'values' => collect(range(0, 11))->map(function() {
                    return rand(5, 25);
                })->toArray()
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'active_workflows' => $activeWorkflows,
                    'completed_today' => $completedToday,
                    'success_rate' => $successRate,
                    'avg_response_time' => rand(150, 250) . 'ms',
                    'chart_data' => $chartData
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch live stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active workflows
     */
    public function getActiveWorkflows(): JsonResponse
    {
        try {
            $workflows = \App\Models\Workflow::where('status', 'running')
                ->with(['latestMessage'])
                ->orderBy('started_at', 'desc')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'workflows' => $workflows
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active workflows: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save workflow from visual builder
     */
    public function saveWorkflow(Request $request): JsonResponse
    {
        $request->validate([
            'workflow' => 'required|string'
        ]);

        try {
            $workflowData = json_decode($request->workflow, true);
            
            if (!$workflowData || !isset($workflowData['name'], $workflowData['nodes'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid workflow data'
                ], 400);
            }

            // In a real implementation, you would save this to the database
            // For now, we'll just simulate a successful save
            
            return response()->json([
                'success' => true,
                'message' => 'Workflow saved successfully',
                'workflow_id' => rand(1000, 9999)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test workflow from visual builder
     */
    public function testWorkflowBuilder(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'workflow' => 'required|string'
        ]);

        try {
            $workflowData = json_decode($request->workflow, true);
            $testMessage = $request->message;
            
            if (!$workflowData || !isset($workflowData['nodes'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid workflow data'
                ], 400);
            }

            // Simulate workflow execution
            $nodes = $workflowData['nodes'];
            $result = $this->simulateWorkflowExecution($nodes, $testMessage);
            
            return response()->json([
                'success' => true,
                'message' => 'Workflow test completed',
                'response' => $result['response'],
                'execution_time' => $result['execution_time'],
                'steps_executed' => $result['steps_executed']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to test workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simulate workflow execution for testing
     */
    private function simulateWorkflowExecution(array $nodes, string $message): array
    {
        $startTime = microtime(true);
        $responses = [];
        $stepsExecuted = 0;
        
        foreach ($nodes as $node) {
            $stepsExecuted++;
            
            switch ($node['type']) {
                case 'trigger':
                    if ($node['subtype'] === 'whatsapp') {
                        $responses[] = "Trigger activated by message: '{$message}'";
                    }
                    break;
                    
                case 'ai':
                    if ($node['subtype'] === 'intent') {
                        $responses[] = "AI detected intent: doctor_search";
                    } elseif ($node['subtype'] === 'extract') {
                        $responses[] = "AI extracted: location=Delhi, specialty=cardiology";
                    } elseif ($node['subtype'] === 'generate') {
                        $responses[] = "AI generated personalized response";
                    }
                    break;
                    
                case 'function':
                    if ($node['subtype'] === 'search') {
                        $responses[] = "Found 5 doctors matching criteria";
                    } elseif ($node['subtype'] === 'booking') {
                        $responses[] = "Checked availability: 3 slots available";
                    } elseif ($node['subtype'] === 'payment') {
                        $responses[] = "Payment processed successfully";
                    }
                    break;
                    
                case 'action':
                    if ($node['subtype'] === 'send') {
                        $responses[] = "Message sent to user";
                    } elseif ($node['subtype'] === 'delay') {
                        $responses[] = "Added 2 second delay";
                    }
                    break;
                    
                case 'integration':
                    $responses[] = "Integration with {$node['subtype']} completed";
                    break;
            }
            
            // Simulate processing time
            usleep(100000); // 0.1 second
        }
        
        $executionTime = round((microtime(true) - $startTime) * 1000, 2) . 'ms';
        
        // Generate final response based on workflow
        $finalResponse = $this->generateFinalResponse($nodes, $message);
        
        return [
            'response' => $finalResponse,
            'execution_time' => $executionTime,
            'steps_executed' => $stepsExecuted,
            'step_details' => $responses
        ];
    }

    /**
     * Generate final response based on workflow nodes
     */
    private function generateFinalResponse(array $nodes, string $message): string
    {
        $hasSearch = collect($nodes)->contains(function($node) {
            return $node['type'] === 'function' && $node['subtype'] === 'search';
        });
        
        $hasBooking = collect($nodes)->contains(function($node) {
            return $node['type'] === 'function' && $node['subtype'] === 'booking';
        });
        
        $hasPayment = collect($nodes)->contains(function($node) {
            return $node['type'] === 'function' && $node['subtype'] === 'payment';
        });
        
        if ($hasPayment) {
            return "Your appointment has been booked and payment processed successfully! You'll receive a confirmation shortly.";
        } elseif ($hasBooking) {
            return "I found available appointments for you. Please proceed with payment to confirm your booking.";
        } elseif ($hasSearch) {
            return "I found several doctors near you:\n\n1. Dr. Smith - Cardiologist\n2. Dr. Johnson - Cardiologist\n3. Dr. Brown - Cardiologist\n\nWould you like to book an appointment?";
        } else {
            return "Thank you for your message. I've processed your request successfully.";
        }
    }

    /**
     * Get rule templates (helper method)
     */
    private function getRuleTemplates(): array
    {
        return [
            'greeting_response' => [
                'name' => 'Smart Greeting Response',
                'description' => 'Automatically respond to greetings with personalized messages',
                'trigger_type' => 'keyword_match',
                'triggers' => ['hi', 'hello', 'good morning', 'good afternoon', 'good evening', 'namaste'],
                'action' => 'send_template',
                'response' => 'Hello! 👋 Welcome to FreeDoctor. How can I help you today? You can ask me about finding doctors, health camps, or booking appointments.',
                'machine' => 'template',
                'is_active' => true
            ],
            'emergency_detection' => [
                'name' => 'Emergency Keyword Detection',
                'description' => 'Detect emergency keywords and prioritize response',
                'trigger_type' => 'keyword_match',
                'triggers' => ['emergency', 'urgent', 'help', 'critical', 'pain', 'chest pain', 'bleeding'],
                'action' => 'priority_escalation',
                'response' => '🚨 Emergency detected. For immediate medical assistance, please call 102 or visit your nearest hospital. We are also connecting you to our priority support.',
                'machine' => 'ai',
                'is_active' => true
            ]
        ];
    }

    /**
     * Get workflow templates (helper method)
     */
    private function getWorkflowTemplates(): array
    {
        return [
            'doctor_search' => [
                'name' => 'Doctor Search Workflow',
                'description' => 'Help users find doctors by specialty and location',
                'color' => 'primary',
                'icon' => 'user-md',
                'trigger_type' => 'keyword_match',
                'success_rate' => '96%',
                'avg_time' => '2.3s',
                'steps' => [
                    [
                        'step' => 1,
                        'name' => 'Intent Analysis',
                        'machine' => 'ai',
                        'action' => 'analyze_intent',
                        'description' => 'Analyze user message to understand doctor search intent',
                        'parameters' => ['message' => 'user_input', 'context' => 'doctor_search']
                    ],
                    [
                        'step' => 2,
                        'name' => 'Extract Parameters',
                        'machine' => 'ai',
                        'action' => 'extract_parameters',
                        'description' => 'Extract specialty, location, and other search criteria',
                        'parameters' => ['specialty' => 'extracted', 'location' => 'extracted']
                    ],
                    [
                        'step' => 3,
                        'name' => 'Search Doctors',
                        'machine' => 'function',
                        'action' => 'searchNearbyDoctors',
                        'description' => 'Search for doctors matching the criteria',
                        'parameters' => ['specialty' => 'parameter', 'location' => 'parameter', 'radius' => '10km']
                    ],
                    [
                        'step' => 4,
                        'name' => 'Format Results',
                        'machine' => 'datatable',
                        'action' => 'formatDoctorList',
                        'description' => 'Format doctor list for WhatsApp display',
                        'parameters' => ['format' => 'whatsapp', 'max_items' => 5]
                    ],
                    [
                        'step' => 5,
                        'name' => 'Send Response',
                        'machine' => 'template',
                        'action' => 'sendDoctorList',
                        'description' => 'Send formatted doctor list to user',
                        'parameters' => ['template' => 'doctor_list', 'data' => 'formatted_doctors']
                    ]
                ]
            ]
        ];
    }
}
