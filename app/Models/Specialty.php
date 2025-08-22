<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class Specialty extends Model
{
    protected $fillable = ['name'];

    // Optional: Relationship with doctors
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'specialty_id');
    }
}
