<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkflowLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'step_number',
        'machine_type',
        'machine_action',
        'input_json',
        'output_json',
        'status',
        'error_message',
        'started_at',
        'ended_at',
        'execution_time_ms'
    ];

    protected $casts = [
        'input_json' => 'array',
        'output_json' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    /**
     * Workflow this log belongs to
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Mark log as started
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'running',
            'started_at' => now()
        ]);
    }

    /**
     * Mark log as completed
     */
    public function markAsCompleted(array $output = null): void
    {
        $data = [
            'status' => 'completed',
            'ended_at' => now(),
            'execution_time_ms' => $this->started_at ? now()->diffInMilliseconds($this->started_at) : null
        ];

        if ($output) {
            $data['output_json'] = $output;
        }

        $this->update($data);
    }

    /**
     * Mark log as failed
     */
    public function markAsFailed(string $error = null): void
    {
        $this->update([
            'status' => 'failed',
            'ended_at' => now(),
            'error_message' => $error
        ]);
    }

    /**
     * Get execution time in seconds
     */
    public function getExecutionTimeInSeconds(): ?float
    {
        return $this->execution_time_ms ? $this->execution_time_ms / 1000 : null;
    }
}
