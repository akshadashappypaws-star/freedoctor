<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplateTableLink extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_template_table_links';

    protected $fillable = [
        'template_id',
        'linked_tables',
        'trigger_event',
        'delay_minutes',
        'priority',
        'table_fields',
        'row_limits',
        'sort_orders',
        'filters',
        'is_active',
        'last_triggered_at',
        'total_sent',
        'success_count',
        'failed_count'
    ];

    protected $casts = [
        'linked_tables' => 'array',
        'table_fields' => 'array',
        'row_limits' => 'array',
        'sort_orders' => 'array',
        'filters' => 'array',
        'is_active' => 'boolean',
        'last_triggered_at' => 'datetime',
        'total_sent' => 'integer',
        'success_count' => 'integer',
        'failed_count' => 'integer'
    ];

    /**
     * Get the WhatsApp template associated with this table link
     */
    public function whatsappTemplate()
    {
        return $this->belongsTo(WhatsappTemplate::class, 'template_id', 'id');
    }

    /**
     * Get linked table data based on configuration
     */
    public function getLinkedTableData()
    {
        $linkedData = [];
        
        foreach ($this->linked_tables as $tableName) {
            if ($tableName === 'all') {
                // Return data from all available tables
                $linkedData['campaigns'] = $this->getTableData('campaigns');
                $linkedData['doctors'] = $this->getTableData('doctors');
                $linkedData['patients'] = $this->getTableData('patients');
                $linkedData['categories'] = $this->getTableData('categories');
                $linkedData['payments'] = $this->getTableData('payments');
                $linkedData['registrations'] = $this->getTableData('registrations');
                $linkedData['messages'] = $this->getTableData('messages');
                break;
            } else {
                $linkedData[$tableName] = $this->getTableData($tableName);
            }
        }
        
        return $linkedData;
    }

    /**
     * Get data from a specific table with filters and limits
     */
    private function getTableData($tableName)
    {
        $rowLimit = $this->row_limits[$tableName] ?? 100;
        $sortOrder = $this->sort_orders[$tableName] ?? 'created_at_desc';
        $filter = $this->filters[$tableName] ?? null;

        switch ($tableName) {
            case 'campaigns':
                $query = \App\Models\Campaign::with(['doctor', 'category']);
                break;
            case 'doctors':
                $query = \App\Models\Doctor::with(['specializations', 'categories']);
                break;
            case 'patients':
                $query = \App\Models\Patient::with(['payments', 'campaign']);
                break;
            case 'categories':
                $query = \App\Models\Category::with(['campaigns']);
                break;
            case 'payments':
                $query = \App\Models\PatientPayment::with(['patient', 'campaign']);
                break;
            case 'registrations':
                $query = \App\Models\Patient::with(['payments', 'campaign']);
                break;
            case 'messages':
                $query = \App\Models\WhatsappMessage::with(['conversation']);
                break;
            default:
                return collect([]);
        }

        // Apply sorting
        [$sortField, $sortDirection] = explode('_', $sortOrder);
        $query->orderBy($sortField, $sortDirection);

        // Apply filters if provided
        if ($filter) {
            $filterParts = explode(',', $filter);
            foreach ($filterParts as $filterPart) {
                if (strpos($filterPart, '=') !== false) {
                    [$field, $value] = explode('=', trim($filterPart));
                    $query->where(trim($field), trim($value));
                }
            }
        }

        return $query->limit($rowLimit)->get();
    }

    /**
     * Update statistics after sending messages
     */
    public function updateStats($sent, $success, $failed)
    {
        $this->increment('total_sent', $sent);
        $this->increment('success_count', $success);
        $this->increment('failed_count', $failed);
        $this->last_triggered_at = now();
        $this->save();
    }

    /**
     * Check if the link should be triggered based on event
     */
    public function shouldTrigger($event)
    {
        return $this->is_active && $this->trigger_event === $event;
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_sent === 0) return 0;
        return round(($this->success_count / $this->total_sent) * 100, 2);
    }
}
