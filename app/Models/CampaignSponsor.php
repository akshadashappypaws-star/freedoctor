<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'name',
        'email',
        'phone_number',
        'address',
        'message',
        'amount',
        'payment_method',
        'payment_status',
        'payment_id',
        'payment_details',
        'payment_date'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
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
            'pending' => 'bg-yellow-100 text-yellow-800',
            'success' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
