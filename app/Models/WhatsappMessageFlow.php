<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessageFlow extends Model
{
    protected $fillable = [
        'name',
        'target_category',
        'flow_steps',
        'success_count',
        'failure_count',
        'is_active'
    ];

    protected $casts = [
        'flow_steps' => 'array',
        'is_active' => 'boolean'
    ];

    public function getSuccessRate()
    {
        $total = $this->success_count + $this->failure_count;
        return $total > 0 ? ($this->success_count / $total) * 100 : 0;
    }
}
