<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminSetting;

class AdminSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminSetting::firstOrCreate(
            ['setting_key' => 'registration_fee_percentage'],
            [
                'setting_name' => 'Patient Registration Fee Percentage',
                'percentage_value' => 5.0,
                'key' => 'registration_fee_percentage', // For backward compatibility
                'value' => '5.0',
                'type' => 'percentage',
                'description' => 'Commission percentage taken from patient registration fees',
                'is_active' => true
            ]
        );

        AdminSetting::firstOrCreate(
            ['setting_key' => 'sponsor_fee_percentage'],
            [
                'setting_name' => 'Sponsor Fee Percentage',
                'percentage_value' => 10.0,
                'key' => 'sponsor_fee_percentage', // For backward compatibility
                'value' => '10.0',
                'type' => 'percentage',
                'description' => 'Commission percentage taken from sponsor payments',
                'is_active' => true
            ]
        );

        AdminSetting::firstOrCreate(
            ['setting_key' => 'doctor_registration_commission'],
            [
                'setting_name' => 'Doctor Registration Commission',
                'percentage_value' => 10.0,
                'key' => 'doctor_registration_commission', // For backward compatibility
                'value' => '10.0',
                'type' => 'percentage',
                'description' => 'Commission percentage taken from doctor registration fees',
                'is_active' => true
            ]
        );

        AdminSetting::firstOrCreate(
            ['setting_key' => 'doctor_subscription_fee'],
            [
                'setting_name' => 'Doctor Subscription Fee',
                'amount' => 1000.0,
                'key' => 'doctor_subscription_fee', // For backward compatibility
                'value' => '1000.0',
                'type' => 'amount',
                'description' => 'Monthly subscription fee for doctors',
                'is_active' => true
            ]
        );
    }
}
