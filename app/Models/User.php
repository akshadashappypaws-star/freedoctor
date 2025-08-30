<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\UserResetPassword;
use App\Models\CampaignReferral;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $guard = 'user';

    protected $fillable = [
        'name', 'username', 'email', 'phone', 'password', 'status', 'your_referral_id', 'referred_by', 'referral_completed_at',
        'bank_account_number', 'bank_ifsc_code', 'bank_name', 'account_holder_name', 
        'razorpay_contact_id', 'razorpay_fund_account_id', 'total_earnings', 'withdrawn_amount', 'available_balance',
        'gender', 'address', 'date_of_birth', 'emergency_contact_name', 'emergency_contact_phone',
        'latitude', 'longitude', 'location_address', 'location_source', 'location_updated_at', 'location_permission_granted', 'ip_address',
        'google_id', 'avatar', 'profile_picture', 'email_verified_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'referral_completed_at' => 'datetime',
        'location_updated_at' => 'datetime',
        'location_permission_granted' => 'boolean',
    ];


public function sendPasswordResetNotification($token)
{
    $this->notify(new UserResetPassword($token));
}

public function sendEmailVerificationNotification()
{
    // Force the correct URL for development
    $baseUrl = config('app.url');
    
    // Use development URL if we're in local environment
    if (app()->environment('local')) {
        $baseUrl = 'http://127.0.0.1:8000';
    }
    
    // Generate simple verification URL (not signed)
    $verificationUrl = $baseUrl . '/user/email/verify/' . $this->getKey() . '/' . sha1($this->getEmailForVerification());
    
    \Illuminate\Support\Facades\Mail::to($this->email)->send(
        new \App\Mail\EmailVerification($this, $verificationUrl)
    );
}

// Referral system relationships
public function referralsMade()
{
    return $this->hasMany(CampaignReferral::class, 'referrer_user_id');
}

public function referralsReceived()
{
    return $this->hasMany(CampaignReferral::class, 'user_id');
}

// Get user's unique referral code for a campaign
public function getReferralCode($campaignId)
{
    return 'REF_' . $this->id . '_' . $campaignId . '_' . substr(md5($this->email . $campaignId), 0, 8);
}

// Generate referral link for a campaign
public function getReferralLink($campaignId)
{
    $baseUrl = config('app.url');
    $referralCode = $this->getReferralCode($campaignId);
    return $baseUrl . '/user/campaigns/' . $campaignId . '?ref=' . $this->your_referral_id . '&campaign=' . $campaignId;
}

// Get total referral earnings
public function getTotalReferralEarnings()
{
    return $this->referralsMade()
               ->where('status', 'completed')
               ->sum('per_campaign_refer_cost');
}

// Generate unique referral ID for user
public static function generateReferralId()
{
    do {
        $referralId = 'USER' . time() . rand(100, 999);
    } while (self::where('your_referral_id', $referralId)->exists());
    
    return $referralId;
}

// Get referrer user
public function referrer()
{
    return $this->belongsTo(User::class, 'referred_by', 'your_referral_id');
}

// Get users referred by this user
public function referredUsers()
{
    return $this->hasMany(User::class, 'referred_by', 'your_referral_id');
}

}
