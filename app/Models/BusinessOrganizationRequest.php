<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessOrganizationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
           'user_id',
        'organization_name',
        'email',
        'phone_number',
        'camp_request_type',
        'specialty_id',
        'date_from',
        'date_to',
        'number_of_people',
        'location',
        'description',
        'hired_doctor_id',
        'status'
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function hiredDoctor()
    {
        return $this->belongsTo(Doctor::class, 'hired_doctor_id');
    }

    public function businessRequests()
    {
        return $this->hasMany(BusinessRequest::class);
    }
}
