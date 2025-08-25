<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappSystemHealth extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_system_health';

    protected $fillable = [
        'component_name',
        'status',
        'health_percentage',
        'requests_today',
        'avg_response_time',
        'success_rate',
        'performance_metrics',
        'last_error',
        'last_check_at'
    ];

    protected $casts = [
        'performance_metrics' => 'array',
        'health_percentage' => 'decimal:2',
        'avg_response_time' => 'decimal:3',
        'success_rate' => 'decimal:2',
        'last_check_at' => 'datetime'
    ];

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    public function updateHealth($metrics = [])
    {
        $this->update([
            'health_percentage' => $metrics['health'] ?? $this->health_percentage,
            'requests_today' => $metrics['requests'] ?? $this->requests_today,
            'avg_response_time' => $metrics['response_time'] ?? $this->avg_response_time,
            'success_rate' => $metrics['success_rate'] ?? $this->success_rate,
            'performance_metrics' => array_merge($this->performance_metrics ?? [], $metrics),
            'last_check_at' => now()
        ]);
    }
}
