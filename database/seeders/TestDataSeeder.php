<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\Campaign;
use App\Models\PatientRegistration;
use App\Models\Specialty;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specialty first
        $specialty = Specialty::firstOrCreate([
            'name' => 'Cardiology'
        ]);

        // Create a doctor
        $doctor = Doctor::firstOrCreate([
            'email' => 'doctor@test.com'
        ], [
            'doctor_name' => 'Dr. John Smith',
            'email_verified_at' => now(),
            'phone' => '9876543210',
            'phone_verified' => true,
            'location' => 'Mumbai',
            'gender' => 'male',
            'specialty_id' => $specialty->id,
            'hospital_name' => 'City Hospital',
            'experience' => 10,
            'description' => 'Experienced cardiologist with 10 years of practice',
            'password' => Hash::make('password'),
            'status' => true
        ]);

        // Create campaigns
        $campaigns = [
            [
                'title' => 'Heart Surgery Campaign',
                'description' => 'Help patients get life-saving heart surgery',
                'location' => 'Mumbai, Maharashtra',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonths(3)->format('Y-m-d'),
                'timings' => '9:00 AM - 5:00 PM',
                'camp_type' => 'free',
                'amount' => null,
                'doctor_id' => $doctor->id,
                'specializations' => json_encode(['Cardiology', 'Heart Surgery']),
                'contact_number' => '9876543210',
                'expected_patients' => 50,
                'images' => json_encode(['heart-campaign.jpg']),
                'video' => null,
                'approval_status' => 'approved',
                'service_in_camp' => 'Heart checkup, ECG, Blood pressure monitoring'
            ],
            [
                'title' => 'Cancer Treatment Support',
                'description' => 'Support cancer patients with treatment costs',
                'location' => 'Delhi',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonths(6)->format('Y-m-d'),
                'timings' => '10:00 AM - 6:00 PM',
                'camp_type' => 'free',
                'amount' => null,
                'doctor_id' => $doctor->id,
                'specializations' => json_encode(['Oncology', 'Cancer Treatment']),
                'contact_number' => '9876543211',
                'expected_patients' => 30,
                'images' => json_encode(['cancer-campaign.jpg']),
                'video' => null,
                'approval_status' => 'approved',
                'service_in_camp' => 'Cancer screening, consultation, treatment planning'
            ],
            [
                'title' => 'Emergency Medical Fund',
                'description' => 'Emergency medical assistance for urgent cases',
                'location' => 'Bangalore, Karnataka',
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addMonths(2)->format('Y-m-d'),
                'timings' => '24/7',
                'camp_type' => 'free',
                'amount' => null,
                'doctor_id' => $doctor->id,
                'specializations' => json_encode(['Emergency Medicine', 'General Medicine']),
                'contact_number' => '9876543212',
                'expected_patients' => 100,
                'images' => json_encode(['emergency-campaign.jpg']),
                'video' => null,
                'approval_status' => 'approved',
                'service_in_camp' => 'Emergency consultation, first aid, referrals'
            ]
        ];

        foreach ($campaigns as $campaignData) {
            $campaign = Campaign::firstOrCreate([
                'title' => $campaignData['title']
            ], $campaignData);

            // Create some patient registrations for each campaign
            PatientRegistration::firstOrCreate([
                'campaign_id' => $campaign->id,
                'email' => 'patient1@example.com'
            ], [
                'name' => 'John Doe',
                'phone_number' => '9876543210',
                'address' => '123 Main Street, City',
                'description' => 'Needs urgent heart surgery',
                'status' => 'yet_to_came'
            ]);

            PatientRegistration::firstOrCreate([
                'campaign_id' => $campaign->id,
                'email' => 'patient2@example.com'
            ], [
                'name' => 'Jane Smith',
                'phone_number' => '9876543211',
                'address' => '456 Park Avenue, City',
                'description' => 'Requires cancer treatment support',
                'status' => 'came'
            ]);
        }

        $this->command->info('Test data created successfully!');
    }
}
