<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappDefaultResponse extends Model
{
    protected $fillable = [
        'topic',
        'question_pattern',
        'answer',
        'template_id',
        'parameters',
        'usage_count',
        'success_rate'
    ];

    protected $casts = [
        'parameters' => 'array',
        'success_rate' => 'float'
    ];

    public function updateStats($wasSuccessful)
    {
        $this->usage_count++;
        $totalResponses = $this->usage_count;
        $successfulResponses = $this->success_rate * ($totalResponses - 1);
        
        if ($wasSuccessful) {
            $successfulResponses++;
        }
        
        $this->success_rate = $successfulResponses / $totalResponses;
        $this->save();
    }
}
