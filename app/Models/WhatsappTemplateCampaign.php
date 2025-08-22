<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplateCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'whatsapp_template_id',
        'campaign_id',
        'trigger_event',
        'dynamic_params',
        'is_active',
        'delay_minutes',
        'conditions'
    ];

    protected $casts = [
        'dynamic_params' => 'array',
        'conditions' => 'array',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function whatsappTemplate()
    {
        return $this->belongsTo(WhatsappTemplate::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Helper methods
    public function getParameterMapping()
    {
        return $this->dynamic_params ?? [];
    }

    public function mapCampaignData($campaign, $additionalData = [])
    {
        $params = [];
        $mapping = $this->getParameterMapping();

        foreach ($mapping as $templateParam => $campaignField) {
            if (strpos($campaignField, '.') !== false) {
                // Handle nested relationships like 'doctor.doctor_name'
                $parts = explode('.', $campaignField);
                $value = $campaign;
                foreach ($parts as $part) {
                    $value = $value->$part ?? '';
                }
                $params[$templateParam] = $value;
            } else {
                // Handle direct campaign fields
                $params[$templateParam] = $campaign->$campaignField ?? '';
            }
        }

        // Add additional data
        foreach ($additionalData as $key => $value) {
            $params[$key] = $value;
        }

        return $params;
    }

    public function shouldSend($campaign, $context = [])
    {
        if (!$this->is_active) {
            return false;
        }

        // Check conditions if any
        if (!empty($this->conditions)) {
            foreach ($this->conditions as $condition) {
                if (!$this->evaluateCondition($condition, $campaign, $context)) {
                    return false;
                }
            }
        }

        return true;
    }

    private function evaluateCondition($condition, $campaign, $context)
    {
        $field = $condition['field'] ?? '';
        $operator = $condition['operator'] ?? '=';
        $value = $condition['value'] ?? '';

        $campaignValue = $campaign->$field ?? null;

        switch ($operator) {
            case '=':
                return $campaignValue == $value;
            case '!=':
                return $campaignValue != $value;
            case '>':
                return $campaignValue > $value;
            case '<':
                return $campaignValue < $value;
            case 'contains':
                return strpos($campaignValue, $value) !== false;
            case 'in':
                return in_array($campaignValue, (array)$value);
            default:
                return true;
        }
    }
}
