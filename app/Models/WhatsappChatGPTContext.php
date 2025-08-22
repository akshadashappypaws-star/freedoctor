<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappChatGPTContext extends Model
{
    protected $fillable = [
        'topic',
        'system_message',
        'context_data',
        'sample_questions',
        'sample_responses',
        'is_active'
    ];

    protected $casts = [
        'context_data' => 'array',
        'is_active' => 'boolean'
    ];
}
