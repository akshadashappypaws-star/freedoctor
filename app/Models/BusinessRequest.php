<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_organization_request_id',
        'doctor_id',
        'status',
        'application_message',
        'applied_at',
        'reviewed_at'
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function businessOrganizationRequest()
    {
        return $this->belongsTo(BusinessOrganizationRequest::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
