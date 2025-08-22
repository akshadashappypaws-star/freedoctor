<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'user_id',
        'whatsapp_number',
        'intent',
        'json_plan',
        'context_data',
        'current_step',
        'total_steps',
        'started_at',
        'completed_at',
        'execution_time_ms'
    ];

    protected $casts = [
        'json_plan' => 'array',
        'context_data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Workflow creator (admin/doctor)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * WhatsApp user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Workflow execution logs
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WorkflowLog::class);
    }

    /**
     * Workflow errors
     */
    public function errors(): HasMany
    {
        return $this->hasMany(WorkflowError::class);
    }

    /**
     * Conversation history
     */
    public function conversationHistory(): HasMany
    {
        return $this->hasMany(WorkflowConversationHistory::class);
    }

    /**
     * Performance metrics
     */
    public function performanceMetrics(): HasMany
    {
        return $this->hasMany(WorkflowPerformanceMetric::class);
    }

    /**
     * Get the latest conversation message
     */
    public function latestMessage()
    {
        return $this->hasOne(WorkflowConversationHistory::class)->latest();
    }

    /**
     * Check if workflow is currently running
     */
    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    /**
     * Check if workflow is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if workflow has failed
     */
    public function hasFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentage(): float
    {
        if ($this->total_steps === 0) {
            return 0;
        }
        return round(($this->current_step / $this->total_steps) * 100, 2);
    }

    /**
     * Mark workflow as started
     */
    public function markAsStarted(): void
    {
        $this->update([
            'status' => 'running',
            'started_at' => now()
        ]);
    }

    /**
     * Mark workflow as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'execution_time_ms' => $this->started_at ? now()->diffInMilliseconds($this->started_at) : null
        ]);
    }

    /**
     * Mark workflow as failed
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now()
        ]);
    }

    /**
     * Increment current step
     */
    public function incrementStep(): void
    {
        $this->increment('current_step');
    }
}
