<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\UserMessage;
use App\Events\UserMessageSent;
use App\Models\DoctorMessage;
use App\Events\DoctorMessageSent;

echo "=== Simulating Admin Approval Process ===\n\n";

// Simulate admin approving a business proposal
// This should trigger notifications to BOTH doctor AND user

// 1. Create doctor notification (admin approved doctor's proposal)
$doctorMessage = DoctorMessage::create([
    'doctor_id' => 6, // Our test doctor
    'type' => 'approval',
    'message' => "🎉 Congratulations! Your business proposal has been approved by the admin. You can now proceed with your business activities.",
    'is_read' => false
]);

echo "✅ Created doctor approval notification (ID: {$doctorMessage->id})\n";

// 2. Create user notification (user's business request got a doctor)
$userMessage = UserMessage::create([
    'user_id' => 16, // Existing user
    'type' => 'approval', 
    'message' => "🎉 Great news! The business proposal from Dr. Test Cardiologist has been approved and is now available for collaboration.",
    'is_read' => false
]);

echo "✅ Created user approval notification (ID: {$userMessage->id})\n";

// 3. Broadcast real-time notifications
try {
    echo "📡 Broadcasting to doctor portal...\n";
    event(new DoctorMessageSent($doctorMessage));
    
    echo "📡 Broadcasting to user portal...\n";
    event(new UserMessageSent($userMessage));
    
    echo "\n🎯 Real-time notifications sent to BOTH portals!\n";
    echo "👨‍⚕️ Doctor notification: Check http://127.0.0.1:8000/doctor/dashboard\n";
    echo "👤 User notification: Check http://127.0.0.1:8000/user/dashboard\n";
    echo "💬 Toast notifications should appear at bottom-right corner on both portals!\n";
    
} catch (\Exception $e) {
    echo "❌ Broadcasting failed: " . $e->getMessage() . "\n";
}
?>
