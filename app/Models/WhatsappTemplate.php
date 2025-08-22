<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $fillable = [
        'name',
        'content',
        'language',
        'category',
        'components',
        'whatsapp_id',
        'status',
        'meta_data',
        'is_active'
    ];

    protected $casts = [
        'components' => 'array',
        'meta_data' => 'array',
        'is_active' => 'boolean'
    ];

    public function bulkMessages()
    {
        return $this->hasMany(WhatsappBulkMessage::class, 'template_id');
    }

    public function conversations()
    {
        return $this->hasMany(WhatsappConversation::class, 'template_id');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'whatsapp_template_campaigns')
                    ->withPivot(['trigger_event', 'dynamic_params', 'is_active', 'delay_minutes', 'conditions'])
                    ->withTimestamps();
    }

    public function templateCampaigns()
    {
        return $this->hasMany(WhatsappTemplateCampaign::class);
    }

    public function getParametersAttribute()
    {
        $components = $this->components ?? [];
        $parameters = [];

        foreach ($components as $component) {
            if (isset($component['parameters'])) {
                foreach ($component['parameters'] as $param) {
                    if (isset($param['text']) && strpos($param['text'], '{{') !== false) {
                        preg_match_all('/\{\{(.+?)\}\}/', $param['text'], $matches);
                        $parameters = array_merge($parameters, $matches[1]);
                    }
                }
            }
        }

        return array_unique($parameters);
    }

    public function isApproved()
    {
        return $this->status === 'APPROVED';
    }
}
