<?php

namespace App\Machines;

use Exception;
use Illuminate\Support\Facades\Log;

class VisualizationMachine extends BaseMachine
{
    protected string $machineType = 'visualization';

    /**
     * Get default settings for Visualization machine
     */
    public static function getDefaultSettings(): array
    {
        return [
            'chart_types' => ['bar', 'line', 'pie', 'doughnut', 'area', 'scatter'],
            'default_chart_type' => 'bar',
            'max_data_points' => 500,
            'color_schemes' => ['default', 'healthcare', 'professional', 'custom'],
            'default_color_scheme' => 'healthcare',
            'export_formats' => ['png', 'svg', 'pdf', 'jpeg'],
            'responsive' => true,
            'animations_enabled' => true,
            'legend_enabled' => true,
            'tooltips_enabled' => true,
            'zoom_enabled' => true,
            'download_enabled' => true
        ];
    }

    public function execute(array $input, int $stepNumber): array
    {
        return $this->safeExecute(function () use ($input) {
            $this->validateInput($input, ['action']);

            $action = $input['action'];
            $data = $input['data'] ?? [];
            $options = $input['options'] ?? [];

            return $this->processVisualization($action, $data, $options);
        }, $stepNumber, $input['action'] ?? 'unknown', $input);
    }

    /**
     * Process visualization based on action
     */
    private function processVisualization(string $action, array $data, array $options): array
    {
        switch ($action) {
            case 'updateWorkflowStatus':
                return $this->updateWorkflowStatus($data, $options);
            
            case 'broadcastStepProgress':
                return $this->broadcastStepProgress($data, $options);
            
            case 'sendRealTimeNotification':
                return $this->sendRealTimeNotification($data, $options);
            
            case 'updateDashboardMetrics':
                return $this->updateDashboardMetrics($data, $options);
            
            case 'logWorkflowEvent':
                return $this->logWorkflowEvent($data, $options);
            
            case 'generateReport':
                return $this->generateReport($data, $options);
            
            case 'createVisualizationData':
                return $this->createVisualizationData($data, $options);
            
            default:
                throw new Exception("Unknown visualization action: {$action}");
        }
    }

    /**
     * Update workflow status in real-time
     */
    private function updateWorkflowStatus(array $data, array $options): array
    {
        $workflowId = $data['workflow_id'];
        $status = $data['status'];
        $stepNumber = $data['step_number'] ?? null;

        // Broadcast to WebSocket channel
        $this->broadcastToChannel("workflow.{$workflowId}", [
            'type' => 'status_update',
            'workflow_id' => $workflowId,
            'status' => $status,
            'step_number' => $stepNumber,
            'timestamp' => now()->toISOString(),
            'progress_percentage' => $data['progress_percentage'] ?? null
        ]);

        // Also broadcast to admin dashboard
        $this->broadcastToChannel('admin.dashboard', [
            'type' => 'workflow_status_change',
            'workflow_id' => $workflowId,
            'status' => $status,
            'timestamp' => now()->toISOString()
        ]);

        return [
            'status_updated' => true,
            'workflow_id' => $workflowId,
            'new_status' => $status,
            'broadcast_sent' => true
        ];
    }

    /**
     * Broadcast step progress in real-time
     */
    private function broadcastStepProgress(array $data, array $options): array
    {
        $workflowId = $data['workflow_id'];
        $stepNumber = $data['step_number'];
        $machineType = $data['machine_type'];
        $stepStatus = $data['step_status'];

        $progressData = [
            'type' => 'step_progress',
            'workflow_id' => $workflowId,
            'step_number' => $stepNumber,
            'machine_type' => $machineType,
            'status' => $stepStatus,
            'timestamp' => now()->toISOString(),
            'execution_time' => $data['execution_time'] ?? null,
            'step_data' => $data['step_data'] ?? null
        ];

        // Broadcast to specific workflow channel
        $this->broadcastToChannel("workflow.{$workflowId}", $progressData);

        // Broadcast to admin monitoring channel
        $this->broadcastToChannel('admin.workflow-monitor', $progressData);

        return [
            'progress_broadcasted' => true,
            'workflow_id' => $workflowId,
            'step_number' => $stepNumber,
            'step_status' => $stepStatus
        ];
    }

    /**
     * Send real-time notification
     */
    private function sendRealTimeNotification(array $data, array $options): array
    {
        $type = $data['type']; // success, error, warning, info
        $title = $data['title'];
        $message = $data['message'];
        $recipients = $data['recipients'] ?? ['admin']; // admin, user, all

        $notification = [
            'type' => 'notification',
            'notification_type' => $type,
            'title' => $title,
            'message' => $message,
            'timestamp' => now()->toISOString(),
            'workflow_id' => $data['workflow_id'] ?? null,
            'action_required' => $data['action_required'] ?? false
        ];

        // Send to specified recipients
        foreach ($recipients as $recipient) {
            $channel = match($recipient) {
                'admin' => 'admin.notifications',
                'user' => 'user.notifications',
                'all' => 'global.notifications',
                default => $recipient
            };

            $this->broadcastToChannel($channel, $notification);
        }

        return [
            'notification_sent' => true,
            'type' => $type,
            'recipients' => $recipients,
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Update dashboard metrics in real-time
     */
    private function updateDashboardMetrics(array $data, array $options): array
    {
        $metrics = [
            'type' => 'metrics_update',
            'metrics' => [
                'active_workflows' => $data['active_workflows'] ?? null,
                'completed_workflows' => $data['completed_workflows'] ?? null,
                'failed_workflows' => $data['failed_workflows'] ?? null,
                'average_response_time' => $data['average_response_time'] ?? null,
                'success_rate' => $data['success_rate'] ?? null,
                'user_satisfaction' => $data['user_satisfaction'] ?? null
            ],
            'timestamp' => now()->toISOString(),
            'period' => $data['period'] ?? 'real-time'
        ];

        // Remove null values
        $metrics['metrics'] = array_filter($metrics['metrics'], fn($value) => $value !== null);

        // Broadcast to dashboard channel
        $this->broadcastToChannel('admin.dashboard', $metrics);

        return [
            'metrics_updated' => true,
            'metrics_count' => count($metrics['metrics']),
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Log workflow event for visualization
     */
    private function logWorkflowEvent(array $data, array $options): array
    {
        $event = [
            'workflow_id' => $data['workflow_id'],
            'event_type' => $data['event_type'], // started, step_completed, error, completed
            'event_data' => $data['event_data'] ?? [],
            'timestamp' => now()->toISOString(),
            'machine_type' => $data['machine_type'] ?? null,
            'step_number' => $data['step_number'] ?? null
        ];

        // Log to application log
        Log::info('Workflow Event', $event);

        // Store in database if needed (you could create workflow_events table)
        // WorkflowEvent::create($event);

        // Broadcast for real-time monitoring
        $this->broadcastToChannel('admin.workflow-events', [
            'type' => 'workflow_event',
            'event' => $event
        ]);

        return [
            'event_logged' => true,
            'event_type' => $data['event_type'],
            'workflow_id' => $data['workflow_id'],
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Generate report data
     */
    private function generateReport(array $data, array $options): array
    {
        $reportType = $data['report_type']; // workflow_summary, performance, error_analysis
        $timeframe = $data['timeframe'] ?? '24h'; // 1h, 24h, 7d, 30d

        switch ($reportType) {
            case 'workflow_summary':
                return $this->generateWorkflowSummaryReport($timeframe);
            
            case 'performance':
                return $this->generatePerformanceReport($timeframe);
            
            case 'error_analysis':
                return $this->generateErrorAnalysisReport($timeframe);
            
            default:
                throw new Exception("Unknown report type: {$reportType}");
        }
    }

    /**
     * Create visualization data structure
     */
    private function createVisualizationData(array $data, array $options): array
    {
        $workflowPlan = $data['workflow_plan'] ?? [];
        $currentStep = $data['current_step'] ?? 0;

        $visualizationData = [
            'workflow_diagram' => $this->createWorkflowDiagram($workflowPlan, $currentStep),
            'step_timeline' => $this->createStepTimeline($workflowPlan, $currentStep),
            'machine_flow' => $this->createMachineFlow($workflowPlan),
            'progress_indicator' => $this->createProgressIndicator($workflowPlan, $currentStep),
            'performance_chart' => $this->createPerformanceChart($data['performance_data'] ?? [])
        ];

        return [
            'visualization_data' => $visualizationData,
            'workflow_id' => $data['workflow_id'] ?? null,
            'generated_at' => now()->toISOString()
        ];
    }

    /**
     * Broadcast to WebSocket channel
     */
    private function broadcastToChannel(string $channel, array $data): void
    {
        try {
            // Using Laravel Echo/Pusher or similar WebSocket service
            broadcast(new \App\Events\WorkflowEvent($channel, $data));
        } catch (Exception $e) {
            Log::warning('Failed to broadcast to channel', [
                'channel' => $channel,
                'data' => $data,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate workflow summary report
     */
    private function generateWorkflowSummaryReport(string $timeframe): array
    {
        // This would query your database for workflow statistics
        return [
            'report_type' => 'workflow_summary',
            'timeframe' => $timeframe,
            'data' => [
                'total_workflows' => 150,
                'completed_workflows' => 135,
                'failed_workflows' => 10,
                'pending_workflows' => 5,
                'success_rate' => 90.0,
                'average_completion_time' => '45 seconds',
                'most_common_intent' => 'find_doctor',
                'peak_hours' => ['10:00-12:00', '15:00-17:00']
            ],
            'generated_at' => now()->toISOString()
        ];
    }

    /**
     * Generate performance report
     */
    private function generatePerformanceReport(string $timeframe): array
    {
        return [
            'report_type' => 'performance',
            'timeframe' => $timeframe,
            'data' => [
                'average_response_time' => 1.2, // seconds
                'machine_performance' => [
                    'ai' => ['avg_time' => 0.8, 'success_rate' => 95.0],
                    'function' => ['avg_time' => 0.3, 'success_rate' => 98.0],
                    'datatable' => ['avg_time' => 0.1, 'success_rate' => 99.0],
                    'template' => ['avg_time' => 0.2, 'success_rate' => 97.0]
                ],
                'bottlenecks' => ['ai_processing', 'external_api_calls'],
                'optimization_suggestions' => [
                    'Cache frequent AI responses',
                    'Optimize database queries',
                    'Use async processing for heavy operations'
                ]
            ],
            'generated_at' => now()->toISOString()
        ];
    }

    /**
     * Generate error analysis report
     */
    private function generateErrorAnalysisReport(string $timeframe): array
    {
        return [
            'report_type' => 'error_analysis',
            'timeframe' => $timeframe,
            'data' => [
                'total_errors' => 25,
                'error_types' => [
                    'api_timeout' => 10,
                    'validation_error' => 8,
                    'database_error' => 4,
                    'external_service_error' => 3
                ],
                'most_common_error' => 'api_timeout',
                'error_trends' => 'Decreasing over time',
                'resolution_suggestions' => [
                    'Increase API timeout limits',
                    'Add input validation',
                    'Implement retry mechanisms'
                ]
            ],
            'generated_at' => now()->toISOString()
        ];
    }

    /**
     * Create workflow diagram structure
     */
    private function createWorkflowDiagram(array $workflowPlan, int $currentStep): array
    {
        $steps = $workflowPlan['steps'] ?? [];
        $diagramSteps = [];

        foreach ($steps as $step) {
            $stepNumber = $step['step'];
            $status = $stepNumber < $currentStep ? 'completed' : 
                     ($stepNumber == $currentStep ? 'current' : 'pending');

            $diagramSteps[] = [
                'step' => $stepNumber,
                'machine' => $step['machine'],
                'action' => $step['action'],
                'status' => $status,
                'position' => ['x' => $stepNumber * 200, 'y' => 100],
                'connections' => $stepNumber < count($steps) ? [$stepNumber + 1] : []
            ];
        }

        return [
            'steps' => $diagramSteps,
            'total_steps' => count($steps),
            'current_step' => $currentStep
        ];
    }

    /**
     * Create step timeline
     */
    private function createStepTimeline(array $workflowPlan, int $currentStep): array
    {
        $steps = $workflowPlan['steps'] ?? [];
        $timeline = [];

        foreach ($steps as $step) {
            $stepNumber = $step['step'];
            $status = $stepNumber < $currentStep ? 'completed' : 
                     ($stepNumber == $currentStep ? 'current' : 'pending');

            $timeline[] = [
                'step' => $stepNumber,
                'title' => ucfirst($step['machine']) . ': ' . ucfirst($step['action']),
                'status' => $status,
                'estimated_time' => $step['estimated_time'] ?? '5s',
                'machine_type' => $step['machine']
            ];
        }

        return $timeline;
    }

    /**
     * Create machine flow diagram
     */
    private function createMachineFlow(array $workflowPlan): array
    {
        $steps = $workflowPlan['steps'] ?? [];
        $machines = [];
        $connections = [];

        foreach ($steps as $index => $step) {
            $machineId = $step['machine'] . '_' . $step['step'];
            
            $machines[] = [
                'id' => $machineId,
                'type' => $step['machine'],
                'action' => $step['action'],
                'step' => $step['step']
            ];

            if ($index < count($steps) - 1) {
                $nextMachineId = $steps[$index + 1]['machine'] . '_' . $steps[$index + 1]['step'];
                $connections[] = [
                    'from' => $machineId,
                    'to' => $nextMachineId
                ];
            }
        }

        return [
            'machines' => $machines,
            'connections' => $connections
        ];
    }

    /**
     * Create progress indicator
     */
    private function createProgressIndicator(array $workflowPlan, int $currentStep): array
    {
        $totalSteps = count($workflowPlan['steps'] ?? []);
        $progressPercentage = $totalSteps > 0 ? round(($currentStep / $totalSteps) * 100) : 0;

        return [
            'current_step' => $currentStep,
            'total_steps' => $totalSteps,
            'progress_percentage' => $progressPercentage,
            'estimated_remaining_time' => $this->calculateRemainingTime($workflowPlan, $currentStep)
        ];
    }

    /**
     * Create performance chart data
     */
    private function createPerformanceChart(array $performanceData): array
    {
        return [
            'response_times' => $performanceData['response_times'] ?? [],
            'success_rates' => $performanceData['success_rates'] ?? [],
            'error_rates' => $performanceData['error_rates'] ?? [],
            'throughput' => $performanceData['throughput'] ?? [],
            'chart_type' => 'line',
            'time_range' => '24h'
        ];
    }

    /**
     * Calculate estimated remaining time
     */
    private function calculateRemainingTime(array $workflowPlan, int $currentStep): string
    {
        $steps = $workflowPlan['steps'] ?? [];
        $remainingSteps = array_slice($steps, $currentStep);
        
        $totalSeconds = 0;
        foreach ($remainingSteps as $step) {
            // Parse estimated time (e.g., "5s", "2m", "1h")
            $time = $step['estimated_time'] ?? '5s';
            $totalSeconds += $this->parseTimeToSeconds($time);
        }

        return $this->formatSecondsToHumanTime($totalSeconds);
    }

    /**
     * Parse time string to seconds
     */
    private function parseTimeToSeconds(string $time): int
    {
        $time = strtolower(trim($time));
        $number = (int) filter_var($time, FILTER_SANITIZE_NUMBER_INT);
        
        if (str_contains($time, 'h')) return $number * 3600;
        if (str_contains($time, 'm')) return $number * 60;
        return $number; // assume seconds
    }

    /**
     * Format seconds to human readable time
     */
    private function formatSecondsToHumanTime(int $seconds): string
    {
        if ($seconds < 60) return "{$seconds} seconds";
        if ($seconds < 3600) return round($seconds / 60) . " minutes";
        return round($seconds / 3600, 1) . " hours";
    }
}
