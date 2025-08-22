<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'earning_type',
        'reference_type',
        'reference_id',
        'original_amount',
        'percentage_rate',
        'commission_amount',
        'net_amount_to_receiver',
        'description'
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'percentage_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount_to_receiver' => 'decimal:2'
    ];

    /**
     * Create earning record
     */
    public static function createEarning($type, $referenceType, $referenceId, $originalAmount, $percentage, $description = null)
    {
        $commissionAmount = ($originalAmount * $percentage) / 100;
        $netAmount = $originalAmount - $commissionAmount;

        return static::create([
            'earning_type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'original_amount' => $originalAmount,
            'percentage_rate' => $percentage,
            'commission_amount' => $commissionAmount,
            'net_amount_to_receiver' => $netAmount,
            'description' => $description
        ]);
    }

    /**
     * Get total earnings by type
     */
    public static function getTotalEarnings($type = null)
    {
        $query = static::query();
        
        if ($type) {
            $query->where('earning_type', $type);
        }
        
        return $query->sum('commission_amount');
    }

    /**
     * Get earnings breakdown
     */
    public static function getEarningsBreakdown()
    {
        return [
            'registration' => static::getTotalEarnings('registration'),
            'sponsor' => static::getTotalEarnings('sponsor'),
            'doctor_registration' => static::getTotalEarnings('doctor_registration'),
            'total' => static::getTotalEarnings()
        ];
    }
}
