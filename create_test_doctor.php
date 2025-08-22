<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Doctor;
use App\Models\Specialty;

echo "=== Creating Test Doctor ===\n\n";

// Create a test doctor for Cardiology
$testDoctor = Doctor::create([
    'doctor_name' => 'Dr. Test Cardiologist',
    'email' => 'test.doctor@freedoctor.com',
    'password' => bcrypt('password123'),
    'phone' => '1234567890',
    'specialty_id' => 1, // Cardiology ID from database
    'status' => true,
    'approved_by_admin' => true,
    'experience' => 5,
    'location' => 'Test City',
    'hospital_name' => 'Test Hospital',
    'gender' => 'Male',
    'description' => 'Test cardiologist for notification system testing'
]);

echo "✅ Test doctor created successfully!\n";
echo "Doctor ID: {$testDoctor->id}\n";
echo "Name: {$testDoctor->doctor_name}\n";
echo "Email: {$testDoctor->email}\n";
echo "Specialty ID: {$testDoctor->specialty_id}\n";
echo "Status: " . ($testDoctor->status ? 'Active' : 'Inactive') . "\n";
echo "Approved: " . ($testDoctor->approved_by_admin ? 'Yes' : 'No') . "\n";
echo "\n";

echo "=== Test Setup Complete ===\n";
echo "Now you can:\n";
echo "1. Go to: http://127.0.0.1:8000/user/organization-camp-request\n";
echo "2. Login as user and fill form with Cardiology specialty\n";
echo "3. Submit the form\n";
echo "4. Login as doctor (test.doctor@freedoctor.com / password123)\n";
echo "5. Check for real-time notifications in doctor dashboard\n";
?>
