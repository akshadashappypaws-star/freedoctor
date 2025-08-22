<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Http\Controllers\User\DashboardController;
use Illuminate\Http\Request;

echo "=== Real-time Notification Test ===\n\n";

// Create a new request
$requestData = [
    'organization_name' => 'Real-time Test Org',
    'email' => 'realtime@test.com',
    'user_id' => 16,
    'phone_number' => '9876543210',
    'camp_request_type' => 'surgical',
    'specialty_id' => 1, // Cardiology
    'date_from' => '2025-08-25',
    'date_to' => '2025-08-26',
    'number_of_people' => 75,
    'location' => 'Real-time Test City',
    'description' => 'Real-time notification test for Cardiology doctors'
];

echo "Submitting business request to trigger real-time notification...\n";
echo "Organization: {$requestData['organization_name']}\n";
echo "Specialty: Cardiology (ID: 1)\n";
echo "Expected to notify Doctor ID: 6\n\n";

try {
    $request = new Request($requestData);
    $controller = new DashboardController();
    
    echo "🚀 Triggering notification...\n";
    $response = $controller->storeOrganizationCampRequest($request);
    
    echo "✅ Business request submitted successfully!\n";
    echo "📱 Real-time notification should appear in doctor dashboard now!\n";
    echo "🔍 Check Doctor ID 6 dashboard for the toast notification.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
