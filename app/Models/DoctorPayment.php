<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'amount',
        'payment_id',
        'order_id',
        'payment_status',
        'payment_details',
        'payment_date',
        'receipt_number',
        'description'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'Pending',
            'success' => 'Success',
            'failed' => 'Failed',
            default => 'Unknown'
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'warning',
            'success' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }
}
