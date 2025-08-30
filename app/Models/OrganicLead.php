<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganicLead extends Model
{
    use HasFactory;

    protected $table = 'organic_leads';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'lead_type',
        'message',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
