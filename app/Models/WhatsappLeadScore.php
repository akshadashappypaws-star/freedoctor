<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLeadScore extends Model
{
    protected $fillable = [
        'phone',
        'category',
        'interaction_score',
        'response_rate',
        'appointment_count',
        'query_count',
        'last_interaction',
        'interaction_history',
        'interest_topics',
        'is_active'
    ];

    protected $casts = [
        'interaction_history' => 'array',
        'interest_topics' => 'array',
        'last_interaction' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function updateScore($interaction)
    {
        $history = $this->interaction_history ?? [];
        $history[] = [
            'type' => $interaction['type'],
            'score' => $interaction['score'],
            'timestamp' => now()
        ];

        $this->interaction_history = $history;
        $this->interaction_score += $interaction['score'];
        $this->updateCategory();
        $this->save();
    }

    private function updateCategory()
    {
        if ($this->interaction_score >= 100) {
            $this->category = 'valuable';
        } elseif ($this->interaction_score >= 50) {
            $this->category = 'average';
        } else {
            $this->category = 'not_interested';
        }
    }
}
