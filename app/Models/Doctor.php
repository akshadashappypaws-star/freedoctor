<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
  
    use App\Notifications\DoctorResetPassword;

class Doctor extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $guard = 'doctor';

    protected $fillable = [
        'doctor_name',
        'email',
        'phone',
        'phone_verified',
        'location',
        'gender',
        'specialty_id',
        'hospital_name',
        'experience',
        'description',
        'intro_video',
        'image',
        'password',
        'email_verified_at',
        'status',
        'approved_by_admin',
        'payment_completed',
        'payment_completed_at',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'withdrawn_amount',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified' => 'boolean',
        'status' => 'boolean',
    ];

    // Optional: define relation with specialty
    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }

    // Relationship with campaigns
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'doctor_id');
    }

    // Relationship with payments
    public function payments()
    {
        return $this->hasMany(DoctorPayment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(DoctorPayment::class)->latest();
    }

    // Relationship with proposals
    public function proposals()
    {
        return $this->hasMany(DoctorProposal::class);
    }

    // Relationship with withdrawals
    public function withdrawals()
    {
        return $this->hasMany(DoctorWithdrawal::class);
    }

    // Check if doctor has completed payment
    public function hasCompletedPayment()
    {
        return $this->payment_completed && $this->payments()->where('payment_status', 'success')->exists();
    }

    // Check if doctor is fully approved (payment + admin approval)
    public function isFullyApproved()
    {
        return $this->hasCompletedPayment() && $this->approved_by_admin;
    }

// In DoctorPayment.php
public function doctor()
{
    return $this->belongsTo(Doctor::class, 'doctor_id');
}

public function doctorMessages()
{
    return $this->hasMany(DoctorMessage::class);
}

public function sendPasswordResetNotification($token)
{
    $this->notify(new DoctorResetPassword($token));
}

public function sendEmailVerificationNotification()
{
    $this->notify(new \App\Notifications\DoctorVerifyEmail());
}
}
