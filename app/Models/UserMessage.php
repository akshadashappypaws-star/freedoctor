<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    protected $fillable = [
        'user_id', 
        'phone',
        'message', 
        'type', 
        'status',
        'metadata',
        'read',
        'is_read',
        'data'
    ];
    
    protected $casts = [
        'read' => 'boolean',
        'is_read' => 'boolean',
        'data' => 'array',
        'metadata' => 'array'
    ];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Create a welcome message after campaign registration
     */
    public static function createRegistrationWelcome($userId, $campaignTitle, $campaignId, $shareUrl = null)
    {
        $message = "Welcome to {$campaignTitle}! Thank you for registering. You'll receive updates about this campaign and ways to help spread the word.";
        
        if ($shareUrl) {
            $message .= " Share your referral link to earn rewards: {$shareUrl}";
        }
        
        return self::create([
            'user_id' => $userId,
            'type' => 'campaign_registration',
            'message' => $message,
            'read' => false,
            'is_read' => false,
            'data' => [
                'campaign_id' => $campaignId,
                'campaign_title' => $campaignTitle,
                'registration_date' => now()->toDateString(),
                'share_url' => $shareUrl
            ]
        ]);
    }
}
