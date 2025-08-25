<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappAutomationRule extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_automation_rules';

    protected $fillable = [
        'name',
        'description',
        'trigger_conditions',
        'actions',
        'priority',
        'is_active',
        'execution_count',
        'success_count',
        'last_executed_at'
    ];

    protected $casts = [
        'trigger_conditions' => 'array',
        'actions' => 'array',
        'is_active' => 'boolean',
        'last_executed_at' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function getSuccessRateAttribute()
    {
        if ($this->execution_count == 0) return 0;
        return round(($this->success_count / $this->execution_count) * 100, 2);
    }

    public function incrementExecution($success = false)
    {
        $this->increment('execution_count');
        if ($success) {
            $this->increment('success_count');
        }
        $this->update(['last_executed_at' => now()]);
    }

    /**
     * Get executions for this automation rule
     */
    public function executions()
    {
        // Return empty collection for now - can be implemented later if needed
        return collect([]);
    }

    /**
     * Get recent executions
     */
    public function recentExecutions($limit = 10)
    {
        // Return empty collection for now - can be implemented later if needed
        return collect([]);
    }
}
