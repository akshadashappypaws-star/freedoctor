<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowLog;
use App\Models\WorkflowError;
use App\Models\WorkflowPerformanceMetric;
use App\Services\WorkflowEngine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class WorkflowController extends Controller
{
    protected WorkflowEngine $workflowEngine;

    public function __construct(WorkflowEngine $workflowEngine)
    {
        $this->middleware('auth:admin');
        $this->workflowEngine = $workflowEngine;
    }

    /**
     * Display workflow dashboard with scenarios
     */
    public function index(Request $request): View
    {
        try {
            $timeRange = $request->get('range', '24h');
            $status = $request->get('status', 'all');
            
            // Calculate date range
            $startDate = match($timeRange) {
                '1h' => now()->subHour(),
                '24h' => now()->subDay(),
                '7d' => now()->subWeek(),
                '30d' => now()->subMonth(),
                default => now()->subDay()
            };

            // Base query
            $query = Workflow::with(['logs', 'errors'])
                ->where('created_at', '>=', $startDate);

            // Filter by status
            if ($status !== 'all') {
                $query->where('status', $status);
            }

            // Get workflows with pagination
            $workflows = $query->orderBy('created_at', 'desc')->paginate(20);
            
            $totalWorkflows = Workflow::count();
            $activeWorkflows = Workflow::where('status', 'running')->count();
            $completedWorkflows = Workflow::where('status', 'completed')->count();
            $failedWorkflows = Workflow::where('status', 'failed')->count();
            
            $successRate = $totalWorkflows > 0 ? round(($completedWorkflows / $totalWorkflows) * 100, 2) : 0;
            
            return view('admin.whatsapp.workflows', compact(
                'workflows', 
                'totalWorkflows', 
                'activeWorkflows', 
                'completedWorkflows', 
                'failedWorkflows',
                'successRate'
            ));
        } catch (\Exception $e) {
            return view('admin.whatsapp.workflows', [
                'workflows' => collect(),
                'totalWorkflows' => 0,
                'activeWorkflows' => 0,
                'completedWorkflows' => 0,
                'failedWorkflows' => 0,
                'successRate' => 0
            ]);
        }
    }

    /**
     * Show workflow creation form
     */
    public function create(): View
    {
        $scenarioTemplates = [
            'find_doctor' => [
                'name' => 'Find Doctor',
                'description' => 'Help users find doctors by specialty and location',
                'steps' => [
                    ['machine' => 'ai', 'action' => 'analyze_intent'],
                    ['machine' => 'function', 'action' => 'searchNearbyDoctors'],
                    ['machine' => 'datatable', 'action' => 'formatDoctorList'],
                    ['machine' => 'template', 'action' => 'sendDoctorList']
                ]
            ],
            'health_camp_info' => [
                'name' => 'Health Camp Information',
                'description' => 'Provide health camp details and registration',
                'steps' => [
                    ['machine' => 'ai', 'action' => 'analyze_intent'],
                    ['machine' => 'function', 'action' => 'findHealthCamps'],
                    ['machine' => 'datatable', 'action' => 'formatHealthCampList'],
                    ['machine' => 'template', 'action' => 'sendHealthCampInfo']
                ]
            ],
            'book_appointment' => [
                'name' => 'Book Appointment',
                'description' => 'Help users book appointments with doctors',
                'steps' => [
                    ['machine' => 'ai', 'action' => 'analyze_intent'],
                    ['machine' => 'function', 'action' => 'checkDoctorAvailability'],
                    ['machine' => 'datatable', 'action' => 'formatAvailableSlots'],
                    ['machine' => 'template', 'action' => 'sendBookingOptions']
                ]
            ],
            'payment_assistance' => [
                'name' => 'Payment Assistance',
                'description' => 'Guide users through payment processes',
                'steps' => [
                    ['machine' => 'ai', 'action' => 'analyze_intent'],
                    ['machine' => 'function', 'action' => 'generatePaymentLink'],
                    ['machine' => 'template', 'action' => 'sendPaymentInstructions']
                ]
            ]
        ];

        return view('admin.whatsapp.workflows-create', compact('scenarioTemplates'));
    }

    /**
     * Store a new workflow
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trigger_keywords' => 'required|string',
            'scenario' => 'required|string',
            'steps' => 'required|array',
            'steps.*.machine' => 'required|string|in:ai,function,datatable,template,visualization',
            'steps.*.action' => 'required|string',
            'steps.*.parameters' => 'sometimes|array',
            'is_active' => 'boolean'
        ]);

        try {
            // Create workflow
            $workflow = Workflow::create([
                'scenario' => $validated['scenario'],
                'trigger_keywords' => $validated['trigger_keywords'],
                'steps' => $validated['steps'],
                'status' => 'pending',
                'user_id' => null, // This will be set when triggered
                'whatsapp_number' => null,
                'is_active' => $validated['is_active'] ?? true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Workflow scenario created successfully',
                'workflow' => $workflow
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show specific workflow details
     */
    public function show(Workflow $workflow): View
    {
        $workflow->load(['user', 'logs.performanceMetric', 'errors']);

        // Get execution timeline
        $timeline = $workflow->logs->map(function ($log) use ($workflow) {
            return [
                'step' => $log->step_number,
                'machine' => $log->machine_type,
                'action' => $log->action,
                'status' => $log->status,
                'timestamp' => $log->created_at,
                'execution_time' => $log->performanceMetric?->execution_time_ms ?? 0,
                'output' => $log->output,
                'error' => $workflow->errors->where('step_number', $log->step_number)->first()?->error_message
            ];
        });

        // Get performance metrics
        $performanceMetrics = [
            'total_execution_time' => $workflow->logs->sum(fn($log) => $log->performanceMetric?->execution_time_ms ?? 0),
            'avg_step_time' => $workflow->logs->avg(fn($log) => $log->performanceMetric?->execution_time_ms ?? 0),
            'slowest_step' => $workflow->logs->sortByDesc(fn($log) => $log->performanceMetric?->execution_time_ms ?? 0)->first(),
            'error_count' => $workflow->errors->count()
        ];

        return view('admin.whatsapp.workflows-show', compact('workflow', 'timeline', 'performanceMetrics'));
    }

    /**
     * Retry a failed workflow
     */
    public function retry(Workflow $workflow): JsonResponse
    {
        if ($workflow->status !== 'failed') {
            return response()->json([
                'success' => false,
                'message' => 'Only failed workflows can be retried'
            ], 400);
        }

        try {
            // Reset workflow status
            $workflow->update(['status' => 'pending']);

            // Clear previous errors
            $workflow->errors()->delete();

            // Execute workflow retry
            $result = $this->workflowEngine->retryWorkflow($workflow->id);

            return response()->json([
                'success' => true,
                'message' => 'Workflow retry initiated successfully',
                'result' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retry workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Live monitoring data for specific workflow
     */
    public function liveMonitor(Workflow $workflow): JsonResponse
    {
        $workflow->load(['logs', 'errors']);

        return response()->json([
            'workflow_id' => $workflow->id,
            'status' => $workflow->status,
            'current_step' => $workflow->current_step,
            'total_steps' => count($workflow->steps),
            'progress_percentage' => count($workflow->steps) > 0 ? 
                round(($workflow->current_step / count($workflow->steps)) * 100, 2) : 0,
            'logs' => $workflow->logs->map(function ($log) {
                return [
                    'step' => $log->step_number,
                    'machine' => $log->machine_type,
                    'action' => $log->action,
                    'status' => $log->status,
                    'timestamp' => $log->created_at->format('H:i:s'),
                    'output_preview' => substr($log->output, 0, 100) . (strlen($log->output) > 100 ? '...' : '')
                ];
            }),
            'errors' => $workflow->errors->map(function ($error) {
                return [
                    'step' => $error->step_number,
                    'machine' => $error->machine_type,
                    'error' => $error->error_message,
                    'timestamp' => $error->created_at->format('H:i:s')
                ];
            }),
            'last_updated' => now()->format('H:i:s')
        ]);
    }

    /**
     * Delete a workflow
     */
    public function destroy(Workflow $workflow): JsonResponse
    {
        try {
            $workflow->delete();

            return response()->json([
                'success' => true,
                'message' => 'Workflow deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete workflow: ' . $e->getMessage()
            ], 500);
        }
    }
}
