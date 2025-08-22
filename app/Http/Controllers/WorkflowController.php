<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\WorkflowLog;
use App\Models\WorkflowError;
use App\Models\WorkflowConversationHistory;
use App\Models\WorkflowPerformanceMetric;
use App\Services\WorkflowEngine;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class WorkflowController extends Controller
{
    private WorkflowEngine $workflowEngine;

    public function __construct(WorkflowEngine $workflowEngine)
    {
        $this->workflowEngine = $workflowEngine;
    }

    /**
     * Get all workflows with pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $status = $request->input('status');
            $search = $request->input('search');

            $query = Workflow::with(['creator', 'user', 'latestMessage'])
                ->orderBy('created_at', 'desc');

            if ($status) {
                $query->where('status', $status);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('whatsapp_number', 'like', "%{$search}%")
                      ->orWhere('intent', 'like', "%{$search}%");
                });
            }

            $workflows = $query->paginate($perPage);

            // Add progress percentage to each workflow
            $workflows->getCollection()->transform(function ($workflow) {
                $workflow->progress_percentage = $workflow->getProgressPercentage();
                return $workflow;
            });

            return response()->json($workflows);

        } catch (Exception $e) {
            Log::error('Failed to fetch workflows', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch workflows'], 500);
        }
    }

    /**
     * Get single workflow details
     */
    public function show(int $workflowId): JsonResponse
    {
        try {
            $workflow = Workflow::with([
                'creator',
                'user',
                'logs' => function ($query) {
                    $query->orderBy('step_number');
                },
                'errors',
                'conversationHistory' => function ($query) {
                    $query->orderBy('created_at');
                },
                'performanceMetrics'
            ])->find($workflowId);

            if (!$workflow) {
                return response()->json(['error' => 'Workflow not found'], 404);
            }

            $workflowData = $workflow->toArray();
            $workflowData['progress_percentage'] = $workflow->getProgressPercentage();

            return response()->json($workflowData);

        } catch (Exception $e) {
            Log::error('Failed to fetch workflow details', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to fetch workflow details'], 500);
        }
    }

    /**
     * Get workflow status
     */
    public function status(int $workflowId): JsonResponse
    {
        try {
            $status = $this->workflowEngine->getWorkflowStatus($workflowId);
            return response()->json($status);

        } catch (Exception $e) {
            Log::error('Failed to get workflow status', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retry failed workflow
     */
    public function retry(int $workflowId): JsonResponse
    {
        try {
            $result = $this->workflowEngine->retryWorkflow($workflowId);
            return response()->json($result);

        } catch (Exception $e) {
            Log::error('Failed to retry workflow', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get workflow logs
     */
    public function logs(int $workflowId): JsonResponse
    {
        try {
            $workflow = Workflow::find($workflowId);
            
            if (!$workflow) {
                return response()->json(['error' => 'Workflow not found'], 404);
            }

            $logs = WorkflowLog::where('workflow_id', $workflowId)
                ->orderBy('step_number')
                ->get();

            return response()->json($logs);

        } catch (Exception $e) {
            Log::error('Failed to fetch workflow logs', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to fetch logs'], 500);
        }
    }

    /**
     * Get workflow errors
     */
    public function errors(int $workflowId): JsonResponse
    {
        try {
            $workflow = Workflow::find($workflowId);
            
            if (!$workflow) {
                return response()->json(['error' => 'Workflow not found'], 404);
            }

            $errors = WorkflowError::where('workflow_id', $workflowId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($errors);

        } catch (Exception $e) {
            Log::error('Failed to fetch workflow errors', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to fetch errors'], 500);
        }
    }

    /**
     * Get conversation history for workflow
     */
    public function conversation(int $workflowId): JsonResponse
    {
        try {
            $workflow = Workflow::find($workflowId);
            
            if (!$workflow) {
                return response()->json(['error' => 'Workflow not found'], 404);
            }

            $conversation = WorkflowConversationHistory::where('workflow_id', $workflowId)
                ->orderBy('created_at')
                ->get();

            return response()->json($conversation);

        } catch (Exception $e) {
            Log::error('Failed to fetch conversation history', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to fetch conversation'], 500);
        }
    }

    /**
     * Get workflow statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_workflows' => Workflow::count(),
                'active_workflows' => Workflow::where('status', 'running')->count(),
                'completed_workflows' => Workflow::where('status', 'completed')->count(),
                'failed_workflows' => Workflow::where('status', 'failed')->count(),
                'pending_workflows' => Workflow::where('status', 'pending')->count(),
                
                // Today's statistics
                'today_workflows' => Workflow::whereDate('created_at', today())->count(),
                'today_completed' => Workflow::whereDate('created_at', today())
                    ->where('status', 'completed')->count(),
                'today_failed' => Workflow::whereDate('created_at', today())
                    ->where('status', 'failed')->count(),
                
                // Performance metrics
                'average_execution_time' => Workflow::where('status', 'completed')
                    ->whereNotNull('execution_time_ms')
                    ->avg('execution_time_ms'),
                'success_rate' => $this->calculateSuccessRate(),
                
                // Popular intents
                'popular_intents' => $this->getPopularIntents(),
                
                // Peak hours
                'peak_hours' => $this->getPeakHours(),
                
                // Error statistics
                'error_stats' => $this->getErrorStatistics()
            ];

            return response()->json($stats);

        } catch (Exception $e) {
            Log::error('Failed to fetch workflow statistics', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch statistics'], 500);
        }
    }

    /**
     * Get workflow performance metrics
     */
    public function performance(Request $request): JsonResponse
    {
        try {
            $timeframe = $request->input('timeframe', '24h'); // 1h, 24h, 7d, 30d
            $metricType = $request->input('metric', 'response_time'); // response_time, success_rate, etc.

            $query = WorkflowPerformanceMetric::where('metric_name', $metricType);

            // Apply timeframe filter
            switch ($timeframe) {
                case '1h':
                    $query->where('created_at', '>=', now()->subHour());
                    break;
                case '24h':
                    $query->where('created_at', '>=', now()->subDay());
                    break;
                case '7d':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case '30d':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
            }

            $metrics = $query->orderBy('created_at')->get();

            // Group by time intervals for chart data
            $chartData = $this->groupMetricsByTime($metrics, $timeframe);

            return response()->json([
                'metric_type' => $metricType,
                'timeframe' => $timeframe,
                'data' => $chartData,
                'summary' => [
                    'average' => $metrics->avg('metric_value'),
                    'min' => $metrics->min('metric_value'),
                    'max' => $metrics->max('metric_value'),
                    'count' => $metrics->count()
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch performance metrics', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch performance data'], 500);
        }
    }

    /**
     * Search workflows
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->input('query');
            $filters = $request->input('filters', []);

            if (empty($query)) {
                return response()->json(['error' => 'Search query is required'], 400);
            }

            $workflows = Workflow::with(['creator', 'user', 'latestMessage'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('whatsapp_number', 'like', "%{$query}%")
                      ->orWhere('intent', 'like', "%{$query}%")
                      ->orWhereHas('conversationHistory', function ($conv) use ($query) {
                          $conv->where('message_content', 'like', "%{$query}%");
                      });
                });

            // Apply filters
            if (isset($filters['status'])) {
                $workflows->whereIn('status', (array) $filters['status']);
            }

            if (isset($filters['date_from'])) {
                $workflows->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (isset($filters['date_to'])) {
                $workflows->whereDate('created_at', '<=', $filters['date_to']);
            }

            if (isset($filters['intent'])) {
                $workflows->whereIn('intent', (array) $filters['intent']);
            }

            $results = $workflows->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json([
                'query' => $query,
                'filters' => $filters,
                'results' => $results,
                'total_found' => $results->count()
            ]);

        } catch (Exception $e) {
            Log::error('Failed to search workflows', [
                'query' => $request->input('query'),
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Search failed'], 500);
        }
    }

    /**
     * Delete workflow
     */
    public function destroy(int $workflowId): JsonResponse
    {
        try {
            $workflow = Workflow::find($workflowId);
            
            if (!$workflow) {
                return response()->json(['error' => 'Workflow not found'], 404);
            }

            // Prevent deletion of running workflows
            if ($workflow->status === 'running') {
                return response()->json(['error' => 'Cannot delete running workflow'], 400);
            }

            $workflow->delete();

            return response()->json(['message' => 'Workflow deleted successfully']);

        } catch (Exception $e) {
            Log::error('Failed to delete workflow', [
                'workflow_id' => $workflowId,
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Failed to delete workflow'], 500);
        }
    }

    // Private helper methods

    private function calculateSuccessRate(): float
    {
        $total = Workflow::count();
        if ($total === 0) return 0;

        $completed = Workflow::where('status', 'completed')->count();
        return round(($completed / $total) * 100, 2);
    }

    private function getPopularIntents(): array
    {
        return Workflow::selectRaw('intent, COUNT(*) as count')
            ->whereNotNull('intent')
            ->groupBy('intent')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->pluck('count', 'intent')
            ->toArray();
    }

    private function getPeakHours(): array
    {
        return Workflow::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereDate('created_at', '>=', now()->subWeek())
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->pluck('count', 'hour')
            ->toArray();
    }

    private function getErrorStatistics(): array
    {
        return [
            'total_errors' => WorkflowError::count(),
            'today_errors' => WorkflowError::whereDate('created_at', today())->count(),
            'error_types' => WorkflowError::selectRaw('error_type, COUNT(*) as count')
                ->groupBy('error_type')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'error_type')
                ->toArray(),
            'recoverable_errors' => WorkflowError::where('is_recoverable', true)->count()
        ];
    }

    private function groupMetricsByTime(\Illuminate\Support\Collection $metrics, string $timeframe): array
    {
        $format = match($timeframe) {
            '1h' => 'H:i',
            '24h' => 'H:00',
            '7d' => 'M-d',
            '30d' => 'M-d',
            default => 'H:i'
        };

        return $metrics->groupBy(function ($metric) use ($format) {
            return $metric->created_at->format($format);
        })->map(function ($group) {
            return [
                'value' => $group->avg('metric_value'),
                'count' => $group->count()
            ];
        })->toArray();
    }
}
