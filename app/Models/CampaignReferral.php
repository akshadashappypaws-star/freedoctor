<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignReferral extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'campaign_id',
        'referrer_user_id',
        'per_campaign_refer_cost',
        'referral_code',
        'registration_completed_at',
        'status',
        'notes'
    ];
    
    protected $casts = [
        'per_campaign_refer_cost' => 'decimal:2',
        'registration_completed_at' => 'datetime',
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_user_id');
    }
    
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    
    // Generate unique referral code
    public static function generateReferralCode($userId, $campaignId)
    {
        return 'REF_' . $userId . '_' . $campaignId . '_' . time();
    }
    
    // Check if referral is completed
    public function isCompleted()
    {
        return $this->status === 'completed';
    }
    
    // Mark referral as completed
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'registration_completed_at' => now()
        ]);
    }
}
