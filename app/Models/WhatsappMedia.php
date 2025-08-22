<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMedia extends Model
{
    protected $fillable = [
        'phone',
        'message_id',
        'media_type',
        'file_path',
        'analysis_data',
        'processed_at',
        'analyzed_at'
    ];

    protected $casts = [
        'analysis_data' => 'array',
        'processed_at' => 'datetime',
        'analyzed_at' => 'datetime'
    ];

    public function conversation()
    {
        return $this->belongsTo(WhatsappConversation::class, 'message_id', 'message_id');
    }
}
