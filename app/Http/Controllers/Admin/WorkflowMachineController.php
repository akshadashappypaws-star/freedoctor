<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkflowMachineConfig;
use App\Machines\AiMachine;
use App\Machines\FunctionMachine;
use App\Machines\DataTableMachine;
use App\Machines\TemplateMachine;
use App\Machines\VisualizationMachine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class WorkflowMachineController extends Controller
{
    private array $machines = [
        'ai' => AiMachine::class,
        'function' => FunctionMachine::class,
        'datatable' => DataTableMachine::class,
        'template' => TemplateMachine::class,
        'visualization' => VisualizationMachine::class
    ];

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display machine configurations
     */
    public function index(): View
    {
        $machineConfigs = WorkflowMachineConfig::all()->groupBy('machine_type');

        $machineInfo = [
            'ai' => [
                'name' => 'AI Machine',
                'description' => 'Handles intent analysis, workflow planning, and AI-powered responses using OpenAI GPT-4',
                'icon' => 'fas fa-brain',
                'color' => 'from-blue-500 to-indigo-600',
                'capabilities' => ['Intent Analysis', 'Response Generation', 'Workflow Planning', 'Natural Language Understanding']
            ],
            'function' => [
                'name' => 'Function Machine',
                'description' => 'Executes business logic, database operations, and integrations with external services',
                'icon' => 'fas fa-cogs',
                'color' => 'from-green-500 to-emerald-600',
                'capabilities' => ['Database Queries', 'API Integrations', 'Business Logic', 'Data Processing']
            ],
            'datatable' => [
                'name' => 'DataTable Machine',
                'description' => 'Formats and structures data for optimal WhatsApp display and user experience',
                'icon' => 'fas fa-table',
                'color' => 'from-purple-500 to-violet-600',
                'capabilities' => ['Data Formatting', 'WhatsApp Optimization', 'List Management', 'Response Structuring']
            ],
            'template' => [
                'name' => 'Template Machine',
                'description' => 'Manages WhatsApp message sending, templates, and media handling',
                'icon' => 'fas fa-envelope',
                'color' => 'from-orange-500 to-red-600',
                'capabilities' => ['Message Sending', 'Template Management', 'Media Handling', 'Delivery Tracking']
            ],
            'visualization' => [
                'name' => 'Visualization Machine',
                'description' => 'Provides real-time monitoring, dashboard updates, and performance analytics',
                'icon' => 'fas fa-chart-line',
                'color' => 'from-teal-500 to-cyan-600',
                'capabilities' => ['Real-time Monitoring', 'Dashboard Updates', 'Analytics', 'Performance Tracking']
            ]
        ];

        return view('admin.whatsapp.machines', compact('machineConfigs', 'machineInfo'));
    }

    /**
     * Show machine configuration details
     */
    public function showConfig(string $machine): View
    {
        if (!isset($this->machines[$machine])) {
            abort(404, 'Machine not found');
        }

        $configs = WorkflowMachineConfig::where('machine_type', $machine)->get();
        $machineClass = $this->machines[$machine];

        // Get machine-specific configuration schema
        $configSchema = $this->getMachineConfigSchema($machine);

        return view('admin.whatsapp.machines.config', compact('machine', 'configs', 'configSchema'));
    }

    /**
     * Update machine configuration
     */
    public function updateConfig(Request $request, string $machine): JsonResponse
    {
        if (!isset($this->machines[$machine])) {
            return response()->json(['success' => false, 'message' => 'Machine not found'], 404);
        }

        $validated = $request->validate([
            'configs' => 'required|array',
            'configs.*.config_key' => 'required|string',
            'configs.*.config_value' => 'required',
            'configs.*.description' => 'sometimes|string'
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['configs'] as $configData) {
                WorkflowMachineConfig::updateOrCreate(
                    [
                        'machine_type' => $machine,
                        'config_key' => $configData['config_key']
                    ],
                    [
                        'config_value' => $configData['config_value'],
                        'description' => $configData['description'] ?? null
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => ucfirst($machine) . ' machine configuration updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update configuration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test machine functionality
     */
    public function testMachine(Request $request, string $machine): JsonResponse
    {
        if (!isset($this->machines[$machine])) {
            return response()->json(['success' => false, 'message' => 'Machine not found'], 404);
        }

        $validated = $request->validate([
            'action' => 'required|string',
            'parameters' => 'sometimes|array',
            'test_data' => 'sometimes|array'
        ]);

        try {
            $machineClass = $this->machines[$machine];
            $machineInstance = new $machineClass();

            // Prepare test parameters
            $testParams = array_merge(
                $validated['parameters'] ?? [],
                $validated['test_data'] ?? []
            );

            // Execute test
            $startTime = microtime(true);
            $result = $machineInstance->execute($validated['action'], $testParams, []);
            $executionTime = (microtime(true) - $startTime) * 1000; // Convert to milliseconds

            return response()->json([
                'success' => true,
                'message' => 'Machine test completed successfully',
                'result' => $result,
                'execution_time_ms' => round($executionTime, 2),
                'machine' => $machine,
                'action' => $validated['action']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Machine test failed: ' . $e->getMessage(),
                'machine' => $machine,
                'action' => $validated['action']
            ], 500);
        }
    }

    /**
     * Get machine-specific configuration schema
     */
    private function getMachineConfigSchema(string $machine): array
    {
        return match($machine) {
            'ai' => [
                'api_key' => [
                    'type' => 'password',
                    'label' => 'OpenAI API Key',
                    'description' => 'Your OpenAI API key for GPT-4 integration',
                    'required' => true
                ],
                'model' => [
                    'type' => 'select',
                    'label' => 'AI Model',
                    'description' => 'The OpenAI model to use for processing',
                    'options' => ['gpt-4', 'gpt-4-turbo', 'gpt-3.5-turbo'],
                    'default' => 'gpt-4'
                ],
                'max_tokens' => [
                    'type' => 'number',
                    'label' => 'Max Tokens',
                    'description' => 'Maximum tokens for AI responses',
                    'default' => 1000,
                    'min' => 100,
                    'max' => 4000
                ],
                'temperature' => [
                    'type' => 'number',
                    'label' => 'Temperature',
                    'description' => 'Creativity level (0.0 - 1.0)',
                    'default' => 0.7,
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.1
                ]
            ],
            'function' => [
                'database_timeout' => [
                    'type' => 'number',
                    'label' => 'Database Timeout (seconds)',
                    'description' => 'Maximum time for database operations',
                    'default' => 30,
                    'min' => 5,
                    'max' => 120
                ],
                'search_radius_km' => [
                    'type' => 'number',
                    'label' => 'Search Radius (km)',
                    'description' => 'Default search radius for location-based queries',
                    'default' => 10,
                    'min' => 1,
                    'max' => 100
                ],
                'max_results' => [
                    'type' => 'number',
                    'label' => 'Max Results',
                    'description' => 'Maximum number of results to return',
                    'default' => 5,
                    'min' => 1,
                    'max' => 20
                ]
            ],
            'datatable' => [
                'max_items_per_message' => [
                    'type' => 'number',
                    'label' => 'Max Items per Message',
                    'description' => 'Maximum items to show in a single WhatsApp message',
                    'default' => 3,
                    'min' => 1,
                    'max' => 10
                ],
                'formatting_style' => [
                    'type' => 'select',
                    'label' => 'Formatting Style',
                    'description' => 'How to format data for WhatsApp',
                    'options' => ['compact', 'detailed', 'bullet_points'],
                    'default' => 'compact'
                ]
            ],
            'template' => [
                'whatsapp_token' => [
                    'type' => 'password',
                    'label' => 'WhatsApp Access Token',
                    'description' => 'Your WhatsApp Business API access token',
                    'required' => true
                ],
                'phone_number_id' => [
                    'type' => 'text',
                    'label' => 'Phone Number ID',
                    'description' => 'WhatsApp Business phone number ID',
                    'required' => true
                ],
                'message_timeout' => [
                    'type' => 'number',
                    'label' => 'Message Timeout (seconds)',
                    'description' => 'Timeout for message sending',
                    'default' => 30,
                    'min' => 5,
                    'max' => 60
                ]
            ],
            'visualization' => [
                'websocket_url' => [
                    'type' => 'url',
                    'label' => 'WebSocket URL',
                    'description' => 'WebSocket server URL for real-time updates',
                    'default' => 'ws://localhost:6001'
                ],
                'broadcast_channel' => [
                    'type' => 'text',
                    'label' => 'Broadcast Channel',
                    'description' => 'Channel name for broadcasting updates',
                    'default' => 'workflow-updates'
                ],
                'update_frequency' => [
                    'type' => 'number',
                    'label' => 'Update Frequency (seconds)',
                    'description' => 'How often to send updates',
                    'default' => 5,
                    'min' => 1,
                    'max' => 60
                ]
            ],
            default => []
        };
    }
}
