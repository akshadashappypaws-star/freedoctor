<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AdminSetting extends Model
{
    use HasFactory;

    protected $table = 'admin_settings';

    protected $fillable = [
        'setting_key',
        'setting_name',
        'percentage_value',
        'amount',
        'description',
        'is_active',
    ];

    protected $casts = [
          'percentage_value' => 'float',
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
     
    public static function getByKey($key)
    {
        return static::where('setting_key', $key)->where('is_active', true)->first();
    }

    /**
     * Get percentage value by key
     */
    public static function getPercentage($key)
    {
        $setting = static::getByKey($key);
        return $setting ? $setting->percentage_value : 0;
    }

}
