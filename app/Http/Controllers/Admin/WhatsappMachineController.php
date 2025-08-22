<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WorkflowMachineConfig;
use App\Models\WorkflowLog;
use App\Models\WorkflowError;
use App\Machines\AiMachine;
use App\Machines\FunctionMachine;
use App\Machines\DataTableMachine;
use App\Machines\TemplateMachine;
use App\Machines\VisualizationMachine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WhatsappMachineController extends Controller
{
    /**
     * Display the machines index page
     */
    public function index()
    {
        // Get machine configurations grouped by type
        $machines = [
            'ai' => [
                'name' => 'AI Machine',
                'description' => 'Intelligent response generation using AI models',
                'status' => $this->getMachineStatus('ai'),
                'configs' => WorkflowMachineConfig::where('machine_type', 'ai')->get(),
                'icon' => 'fas fa-robot',
                'color' => 'blue'
            ],
            'function' => [
                'name' => 'Function Machine',
                'description' => 'Execute custom functions and API calls',
                'status' => $this->getMachineStatus('function'),
                'configs' => WorkflowMachineConfig::where('machine_type', 'function')->get(),
                'icon' => 'fas fa-cogs',
                'color' => 'green'
            ],
            'datatable' => [
                'name' => 'DataTable Machine',
                'description' => 'Process and analyze data tables',
                'status' => $this->getMachineStatus('datatable'),
                'configs' => WorkflowMachineConfig::where('machine_type', 'datatable')->get(),
                'icon' => 'fas fa-table',
                'color' => 'purple'
            ],
            'template' => [
                'name' => 'Template Machine',
                'description' => 'Generate responses from templates',
                'status' => $this->getMachineStatus('template'),
                'configs' => WorkflowMachineConfig::where('machine_type', 'template')->get(),
                'icon' => 'fas fa-file-alt',
                'color' => 'orange'
            ],
            'visualization' => [
                'name' => 'Visualization Machine',
                'description' => 'Create charts and visual representations',
                'status' => $this->getMachineStatus('visualization'),
                'configs' => WorkflowMachineConfig::where('machine_type', 'visualization')->get(),
                'icon' => 'fas fa-chart-line',
                'color' => 'red'
            ]
        ];

        // Performance statistics
        $stats = [
            'total_executions' => WorkflowLog::count(),
            'successful_executions' => WorkflowLog::where('status', 'completed')->count(),
            'failed_executions' => WorkflowLog::where('status', 'failed')->count(),
            'active_machines' => WorkflowMachineConfig::where('is_active', true)->count(),
        ];

        // Recent activity
        $recentLogs = WorkflowLog::with(['workflow'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.whatsapp.machines', compact(
            'machines',
            'stats',
            'recentLogs'
        ));
    }

    /**
     * Show machine configuration page
     */
    public function config($machine)
    {
        $validMachines = ['ai', 'function', 'datatable', 'template', 'visualization'];
        
        if (!in_array($machine, $validMachines)) {
            abort(404, 'Machine not found');
        }

        $configs = WorkflowMachineConfig::where('machine_type', $machine)->get();
        $machineClass = $this->getMachineClass($machine);
        
        // Get machine-specific settings
        $settings = $machineClass ? $machineClass::getDefaultSettings() : [];
        
        // Performance metrics for this machine
        $performance = [
            'total_runs' => WorkflowLog::where('machine_type', $machine)->count(),
            'successful_runs' => WorkflowLog::where('machine_type', $machine)
                ->where('status', 'completed')->count(),
            'failed_runs' => WorkflowLog::where('machine_type', $machine)
                ->where('status', 'failed')->count(),
            'avg_execution_time' => WorkflowLog::where('machine_type', $machine)
                ->where('status', 'completed')
                ->avg('execution_time_ms') ?? 0,
        ];

        // Recent errors for this machine
        $recentErrors = WorkflowError::where('machine_type', $machine)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.pages.whatsapp.machines.config', compact(
            'machine',
            'configs',
            'settings',
            'performance',
            'recentErrors'
        ));
    }

    /**
     * Update machine configuration
     */
    public function updateConfig(Request $request, $machine)
    {
        $request->validate([
            'config_key' => 'required|string|max:255',
            'config_value' => 'required',
            'config_type' => 'required|in:string,integer,boolean,json',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $config = WorkflowMachineConfig::updateOrCreate(
            [
                'machine_type' => $machine,
                'config_key' => $request->config_key
            ],
            [
                'config_value' => $request->config_value,
                'config_type' => $request->config_type,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', true),
                'updated_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Configuration updated successfully',
            'config' => $config
        ]);
    }

    /**
     * Test machine functionality
     */
    public function testMachine(Request $request, $machine)
    {
        $request->validate([
            'test_data' => 'required|array'
        ]);

        try {
            $machineClass = $this->getMachineClass($machine);
            
            if (!$machineClass) {
                throw new \Exception("Machine class not found for: {$machine}");
            }

            $instance = new $machineClass();
            $result = $instance->process($request->test_data);

            return response()->json([
                'success' => true,
                'message' => 'Machine test completed successfully',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Machine test failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get machine performance analytics
     */
    public function analytics($machine)
    {
        // Daily execution count for last 30 days
        $dailyExecutions = WorkflowLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('machine_type', $machine)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Success rate
        $totalExecutions = WorkflowLog::where('machine_type', $machine)->count();
        $successfulExecutions = WorkflowLog::where('machine_type', $machine)
            ->where('status', 'completed')->count();
        $successRate = $totalExecutions > 0 ? ($successfulExecutions / $totalExecutions) * 100 : 0;

        // Average execution time
        $avgExecutionTime = WorkflowLog::where('machine_type', $machine)
            ->where('status', 'completed')
            ->avg('execution_time_ms') ?? 0;

        // Error distribution
        $errorTypes = WorkflowError::where('machine_type', $machine)
            ->select('error_type', DB::raw('COUNT(*) as count'))
            ->groupBy('error_type')
            ->get();

        return response()->json([
            'dailyExecutions' => $dailyExecutions,
            'successRate' => round($successRate, 2),
            'avgExecutionTime' => round($avgExecutionTime, 2),
            'errorTypes' => $errorTypes
        ]);
    }

    /**
     * Toggle machine status
     */
    public function toggleStatus($machine, $configId)
    {
        $config = WorkflowMachineConfig::where('machine_type', $machine)
            ->findOrFail($configId);

        $config->update([
            'is_active' => !$config->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Machine status updated successfully',
            'is_active' => $config->is_active
        ]);
    }

    /**
     * Delete machine configuration
     */
    public function deleteConfig($machine, $configId)
    {
        $config = WorkflowMachineConfig::where('machine_type', $machine)
            ->findOrFail($configId);

        $config->delete();

        return response()->json([
            'success' => true,
            'message' => 'Configuration deleted successfully'
        ]);
    }

    /**
     * Get machine status based on configurations and recent activity
     */
    private function getMachineStatus($machineType)
    {
        $activeConfigs = WorkflowMachineConfig::where('machine_type', $machineType)
            ->where('is_active', true)
            ->count();

        $recentFailures = WorkflowError::where('machine_type', $machineType)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->count();

        if ($activeConfigs === 0) {
            return 'inactive';
        } elseif ($recentFailures > 10) {
            return 'error';
        } else {
            return 'active';
        }
    }

    /**
     * Get machine class based on type
     */
    private function getMachineClass($machineType)
    {
        $classes = [
            'ai' => AiMachine::class,
            'function' => FunctionMachine::class,
            'datatable' => DataTableMachine::class,
            'template' => TemplateMachine::class,
            'visualization' => VisualizationMachine::class,
        ];

        return $classes[$machineType] ?? null;
    }

    /**
     * Export machine configurations
     */
    public function exportConfigs($machine)
    {
        $configs = WorkflowMachineConfig::where('machine_type', $machine)->get();

        $exportData = $configs->map(function ($config) {
            return [
                'Config Name' => $config->config_name,
                'Config Value' => $config->config_value,
                'Description' => $config->description,
                'Is Active' => $config->is_active ? 'Yes' : 'No',
                'Created At' => $config->created_at->format('Y-m-d H:i:s'),
                'Updated At' => $config->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $exportData,
            'filename' => "{$machine}_machine_configs_" . date('Y-m-d') . '.json'
        ]);
    }
}
