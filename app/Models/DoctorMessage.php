<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorMessage extends Model
{
    protected $fillable = [
        'doctor_id',
        'campaign_id',
        'user_id',
        'type',
        'message',
        'amount',
        'read',
        'data'
    ];

    protected $casts = [
        'read' => 'boolean',
        'data' => 'array'
    ];

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
