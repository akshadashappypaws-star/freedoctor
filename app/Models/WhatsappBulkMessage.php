<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappBulkMessage extends Model
{
    protected $fillable = [
        'template_id',
        'recipients',
        'parameters',
        'is_scheduled',
        'scheduled_at',
        'status',
        'total_recipients',
        'sent_count',
        'failed_count',
        'target_category',
        'flow_id',
        'total_numbers',
        'pending_count',
        'invalid_numbers',
        'delivery_status',
        'started_at',
        'completed_at',
        'progress_percentage'
    ];

    protected $casts = [
        'recipients' => 'array',
        'parameters' => 'array',
        'invalid_numbers' => 'array',
        'delivery_status' => 'array',
        'is_scheduled' => 'boolean',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function template()
    {
        return $this->belongsTo(WhatsappTemplate::class, 'template_id');
    }

    public function flow()
    {
        return $this->belongsTo(WhatsappMessageFlow::class, 'flow_id');
    }
}
