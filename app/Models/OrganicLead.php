<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganicLead extends Model
{
    use HasFactory;

    protected $table = 'organiclead';

    protected $fillable = [
        'name',
        'mobile',
        'location',
        'category',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
