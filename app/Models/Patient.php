<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patient_registrations';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'age',
        'gender',
        'address',
        'campaign_id',
        'doctor_id',
        'payment_status',
        'amount',
        'registration_date',
        'status'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the campaign associated with the patient
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the doctor associated with the patient
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get patient payments
     */
    public function payments()
    {
        return $this->hasMany(PatientPayment::class, 'patient_id', 'id');
    }

    /**
     * Get patient registrations (self-reference for compatibility)
     */
    public function registrations()
    {
        return $this->hasMany(Patient::class, 'id', 'id');
    }

    /**
     * Get the patient's full address
     */
    public function getFullAddressAttribute()
    {
        return $this->address;
    }

    /**
     * Check if patient has paid
     */
    public function hasPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get patient's total payments
     */
    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->sum('amount');
    }
}
