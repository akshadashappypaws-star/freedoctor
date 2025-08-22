<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowPerformanceMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'metric_name',
        'metric_value',
        'metric_unit',
        'additional_data',
        'metric_date'
    ];

    protected $casts = [
        'metric_value' => 'decimal:4',
        'additional_data' => 'array',
        'metric_date' => 'date',
    ];

    /**
     * Workflow this metric belongs to
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Static method to record response time
     */
    public static function recordResponseTime(int $workflowId, float $timeInMs): self
    {
        return self::create([
            'workflow_id' => $workflowId,
            'metric_name' => 'response_time',
            'metric_value' => $timeInMs,
            'metric_unit' => 'ms',
            'metric_date' => now()->toDateString()
        ]);
    }

    /**
     * Static method to record success rate
     */
    public static function recordSuccessRate(int $workflowId, float $percentage): self
    {
        return self::create([
            'workflow_id' => $workflowId,
            'metric_name' => 'success_rate',
            'metric_value' => $percentage,
            'metric_unit' => 'percentage',
            'metric_date' => now()->toDateString()
        ]);
    }

    /**
     * Static method to record user satisfaction
     */
    public static function recordUserSatisfaction(int $workflowId, float $score, ?array $additionalData = null): self
    {
        return self::create([
            'workflow_id' => $workflowId,
            'metric_name' => 'user_satisfaction',
            'metric_value' => $score,
            'metric_unit' => 'score',
            'additional_data' => $additionalData,
            'metric_date' => now()->toDateString()
        ]);
    }

    /**
     * Static method to record step completion time
     */
    public static function recordStepCompletionTime(int $workflowId, string $stepName, float $timeInMs): self
    {
        return self::create([
            'workflow_id' => $workflowId,
            'metric_name' => "step_completion_time_{$stepName}",
            'metric_value' => $timeInMs,
            'metric_unit' => 'ms',
            'metric_date' => now()->toDateString()
        ]);
    }
}
