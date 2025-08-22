<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_registration_id',
        'campaign_id',
        'type',
        'amount',
        'payment_id',
        'order_id',
        'payment_status',
        'payment_method',
        'transaction_id',
        'razorpay_payout_id',
        'bank_details',
        'payment_details',
        'receipt_number',
        'payment_date',
        'processed_at',
        'admin_commission',
        'doctor_amount',
        'description',
        'failure_reason'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'bank_details' => 'array',
        'payment_date' => 'datetime',
        'processed_at' => 'datetime',
        'amount' => 'decimal:2',
        'admin_commission' => 'decimal:2',
        'doctor_amount' => 'decimal:2'
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patientRegistration()
    {
        return $this->belongsTo(PatientRegistration::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'success');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdrawal');
    }

    public function scopeRegistrationPayments($query)
    {
        return $query->where('type', 'registration');
    }

    /**
     * Calculate commission based on admin settings
     */
    public function calculateCommission()
    {
        if ($this->type === 'registration') {
            $percentage = AdminSetting::getPercentage('registration_fee_percentage') ?? 10;
            $this->admin_commission = ($this->amount * $percentage) / 100;
            $this->doctor_amount = $this->amount - $this->admin_commission;
            $this->save();
        }
    }

    /**
     * Get payment status label and badge class
     */
    public function getPaymentStatusLabel()
    {
        return match($this->payment_status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'success' => 'Success',
            'completed' => 'Completed',
            'failed' => 'Failed',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClass()
    {
        return match($this->payment_status) {
            'pending' => 'bg-warning text-dark',
            'processing' => 'bg-info text-white',
            'success' => 'bg-success text-white',
            'completed' => 'bg-success text-white',
            'failed' => 'bg-danger text-white',
            default => 'bg-secondary text-white'
        };
    }

    public function getFormattedAmount()
    {
        return '₹' . number_format($this->amount, 2);
    }
}
