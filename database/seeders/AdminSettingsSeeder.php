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
                'description' => 'Commission percentage taken from patient registration fees',
                'is_active' => true
            ]
        );

        AdminSetting::firstOrCreate(
            ['setting_key' => 'sponsor_fee_percentage'],
            [
                'setting_name' => 'Sponsor Fee Percentage',
                'percentage_value' => 10.0,
                'description' => 'Commission percentage taken from sponsor payments',
                'is_active' => true
            ]
        );

        AdminSetting::firstOrCreate(
            ['setting_key' => 'doctor_registration_commission'],
            [
                'setting_name' => 'Doctor Registration Commission',
                'percentage_value' => 10.0,
                'description' => 'Commission percentage taken from doctor registration fees',
                'is_active' => true
            ]
        );
    }
}
