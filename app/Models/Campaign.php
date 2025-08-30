<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'budget',
        'target_audience',
        'requirements',
        'status',
        'location',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'camp_type',
        'registration_payment',
        'per_refer_cost',
        'amount',
        'doctor_id',
        'category_id',
        'specializations',
        'contact_number',
        'expected_patients',
        'images',
        'thumbnail',
        'video',
        'approval_status',
        'service_in_camp'
    ];

    protected $casts = [
        'specializations' => 'array',
        'images' => 'array',
        'budget' => 'decimal:2',
        'per_refer_cost' => 'decimal:2',
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sponsors()
    {
        return $this->hasMany(CampaignSponsor::class);
    }

    // Alias for compatibility with sponsors page
    public function campaignSponsors()
    {
        return $this->hasMany(CampaignSponsor::class);
    }
    public function getSpecializationNamesAttribute()
{
    $ids = is_array($this->specializations)
        ? $this->specializations
        : json_decode($this->specializations ?? '[]', true);

    return Specialty::whereIn('id', $ids)->pluck('name')->toArray();
}


    public function patients()
    {
        return $this->hasMany(PatientRegistration::class);
    }

    public function patientRegistrations()
    {
        return $this->hasMany(PatientRegistration::class);
    }

    public function patientPayments()
    {
        return $this->hasMany(PatientPayment::class);
    }

    public function referrals()
    {
        return $this->hasMany(CampaignReferral::class);
    }

    public function whatsappTemplates()
    {
        return $this->belongsToMany(WhatsappTemplate::class, 'whatsapp_template_campaigns')
                    ->withPivot(['trigger_event', 'dynamic_params', 'is_active', 'delay_minutes', 'conditions'])
                    ->withTimestamps();
    }

    public function whatsappTemplateCampaigns()
    {
        return $this->hasMany(WhatsappTemplateCampaign::class);
    }

    /**
     * Get total referrals count for this campaign
     */
    public function getTotalReferralsCount()
    {
        return $this->referrals()->where('status', 'completed')->count();
    }

    /**
     * Get total referral earnings for this campaign
     */
    public function getTotalReferralEarnings()
    {
        return $this->referrals()
                   ->where('status', 'completed')
                   ->sum('per_campaign_refer_cost');
    }

    /**
     * Get campaign type label
     */
    public function getCampTypeLabel()
    {
        return match($this->camp_type) {
            'medical' => 'Medical',
            'surgical' => 'Surgical',
            default => 'Unknown'
        };
    }

    /**
     * Calculate total registration earnings
     */
    public function getTotalRegistrationEarnings()
    {
        return $this->patientPayments()
                   ->where('payment_status', 'success')
                   ->sum('doctor_amount');
    }

    /**
     * Calculate total sponsor earnings
     */
    public function getTotalSponsorEarnings()
    {
        $totalSponsorAmount = $this->sponsors()->sum('amount');
        $sponsorPercentage = AdminSetting::getPercentage('sponsor_fee_percentage');
        $adminCommission = ($totalSponsorAmount * $sponsorPercentage) / 100;
        return $totalSponsorAmount - $adminCommission;
    }
}
