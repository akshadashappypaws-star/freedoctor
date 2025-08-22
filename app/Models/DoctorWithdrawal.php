<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'amount',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'status',
        'admin_remarks',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relationship with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    // Check if withdrawal is pending
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // Check if withdrawal is completed
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Check if withdrawal is rejected
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
