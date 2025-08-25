<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappAiAnalysis extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_ai_analysis';

    protected $fillable = [
        'conversation_id',
        'message_id',
        'analysis_type',
        'analysis_result',
        'confidence_score',
        'analysis_data',
        'ai_notes',
        'ai_model_used'
    ];

    protected $casts = [
        'analysis_data' => 'array',
        'confidence_score' => 'decimal:2'
    ];

    public function conversation()
    {
        return $this->belongsTo(WhatsappConversation::class, 'conversation_id');
    }

    public function message()
    {
        return $this->belongsTo(WhatsappMessage::class, 'message_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('analysis_type', $type);
    }

    public function scopeHighConfidence($query)
    {
        return $query->where('confidence_score', '>=', 80);
    }
}
