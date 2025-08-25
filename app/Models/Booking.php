<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_patient_registrations'; // WhatsApp-specific registrations table

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'campaign_id',
        'registration_date',
        'registration_time',
        'status',
        'notes',
        'amount',
        'payment_status'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'registration_time' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the patient that owns the registration
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor associated with the registration
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the campaign associated with the registration
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Check if registration is confirmed
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if registration is completed
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Get formatted registration time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->registration_time?->format('h:i A');
    }
}
