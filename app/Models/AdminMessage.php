<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    protected $fillable = ['admin_id', 'message', 'type', 'read', 'data'];
    
    protected $casts = [
        'read' => 'boolean',
        'data' => 'array'
    ];
    
    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
