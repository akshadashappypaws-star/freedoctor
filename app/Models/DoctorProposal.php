<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'business_organization_request_id',
        'message',
        'status',
        'approved_at',
        'approved_by',
        'admin_remarks'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function businessOrganizationRequest()
    {
        return $this->belongsTo(BusinessOrganizationRequest::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
