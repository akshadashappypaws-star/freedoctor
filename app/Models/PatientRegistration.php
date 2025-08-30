<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'patient_name',
        'phone_number',
        'name',
        'phone',
        'email',
        'age',
        'gender',
        'medical_history',
        'emergency_contact',
        'address',
        'description',
        'registration_reason',
        'status',
        'amount',
        'payment_status',
        'payment_method',
        'transaction_id',
        'payment_id',
        'payment_date',
        'payment_amount'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}




    // Helper method to get status with proper formatting
    public function getStatusWithLogic()
    {
        // Safety check for campaign relationship
        if (!$this->campaign) {
            return $this->status ?? 'yet_to_came';
        }

        $today = now()->toDateString();
        $campaignStartDate = $this->campaign->start_date;
        $campaignEndDate = $this->campaign->end_date;

        // Safety checks for dates
        if (!$campaignStartDate || !$campaignEndDate) {
            return $this->status ?? 'yet_to_came';
        }

        if ($today < $campaignStartDate) {
            return 'yet_to_came';
        } elseif ($today >= $campaignStartDate && $today <= $campaignEndDate) {
            return $this->status ?? 'yet_to_came'; // During campaign period, use actual status
        } else {
            // Campaign has ended
            return $this->status === 'came' ? 'came' : 'not_came';
        }
    }

    public function getStatusLabel()
    {
        $status = $this->getStatusWithLogic();
        return match($status) {
            'yet_to_came' => 'Yet to Come',
            'came' => 'Came',
            'not_came' => 'Not Came',
            default => 'Unknown'
        };
    }
}
