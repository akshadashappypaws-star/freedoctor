<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowError extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'workflow_log_id',
        'step_number',
        'machine_type',
        'error_type',
        'error_message',
        'stack_trace',
        'context_data',
        'is_recoverable',
        'recovery_suggestion'
    ];

    protected $casts = [
        'context_data' => 'array',
        'is_recoverable' => 'boolean',
    ];

    /**
     * Workflow this error belongs to
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Workflow log this error belongs to
     */
    public function workflowLog(): BelongsTo
    {
        return $this->belongsTo(WorkflowLog::class);
    }

    /**
     * Static method to create error
     */
    public static function createError(
        int $workflowId,
        int $stepNumber,
        string $machineType,
        string $errorType,
        string $errorMessage,
        ?int $workflowLogId = null,
        ?string $stackTrace = null,
        ?array $contextData = null,
        bool $isRecoverable = false,
        ?string $recoverySuggestion = null
    ): self {
        return self::create([
            'workflow_id' => $workflowId,
            'workflow_log_id' => $workflowLogId,
            'step_number' => $stepNumber,
            'machine_type' => $machineType,
            'error_type' => $errorType,
            'error_message' => $errorMessage,
            'stack_trace' => $stackTrace,
            'context_data' => $contextData,
            'is_recoverable' => $isRecoverable,
            'recovery_suggestion' => $recoverySuggestion
        ]);
    }
}
