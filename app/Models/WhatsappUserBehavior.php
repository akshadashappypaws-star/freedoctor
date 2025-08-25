<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappUserBehavior extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_user_behavior';

    protected $fillable = [
        'conversation_id',
        'interest_level',
        'response_pattern',
        'engagement_type',
        'total_messages',
        'questions_asked',
        'appointments_requested',
        'avg_response_time',
        'interaction_history',
        'last_analyzed_at'
    ];

    protected $casts = [
        'interaction_history' => 'array',
        'avg_response_time' => 'decimal:2',
        'last_analyzed_at' => 'datetime'
    ];

    public function conversation()
    {
        return $this->belongsTo(WhatsappConversation::class, 'conversation_id');
    }

    public function scopeInterested($query)
    {
        return $query->where('engagement_type', 'interested');
    }

    public function scopeAverage($query)
    {
        return $query->where('engagement_type', 'average');
    }

    public function scopeNotInterested($query)
    {
        return $query->where('engagement_type', 'not_interested');
    }

    public function getEngagementScoreAttribute()
    {
        $score = 0;
        $score += $this->total_messages * 2;
        $score += $this->questions_asked * 5;
        $score += $this->appointments_requested * 10;
        
        if ($this->avg_response_time && $this->avg_response_time < 60) {
            $score += 15; // Quick responder bonus
        }

        return min($score, 100); // Cap at 100
    }
}
