<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WhatsappChatGPTPrompt;

echo "=== Creating Default WhatsApp ChatGPT Prompts ===\n";

try {
    $prompts = [
        [
            'name' => 'Medical Consultation Assistant',
            'prompt' => 'You are a helpful medical consultation assistant for FreeDoctor platform. Provide general health information and guidance. Always remind users to consult with qualified medical professionals for serious health concerns. Variables: {patient_name}, {concern}, {doctor_available}',
            'description' => 'Assists patients with general medical questions and directs them to appropriate doctors',
            'category' => 'medical',
            'variables' => ['patient_name', 'concern', 'doctor_available'],
            'example_response' => 'Hello {patient_name}, I understand your concern about {concern}. While I can provide general information, I recommend consulting with one of our qualified doctors. {doctor_available}',
            'priority' => 10,
            'is_active' => true
        ],
        [
            'name' => 'Appointment Booking Assistant',
            'prompt' => 'You are an appointment booking assistant for FreeDoctor platform. Help patients book appointments with doctors, check availability, and provide scheduling information. Variables: {patient_name}, {doctor_name}, {available_slots}, {appointment_type}',
            'description' => 'Helps patients book and manage appointments',
            'category' => 'appointment',
            'variables' => ['patient_name', 'doctor_name', 'available_slots', 'appointment_type'],
            'example_response' => 'Hi {patient_name}! I can help you book an appointment with Dr. {doctor_name}. Available slots: {available_slots}',
            'priority' => 9,
            'is_active' => true
        ],
        [
            'name' => 'General Platform Assistant',
            'prompt' => 'You are a helpful assistant for the FreeDoctor platform. Provide information about our services, help with navigation, and answer general questions about our medical camp booking system. Variables: {user_name}, {query_type}, {service_info}',
            'description' => 'General purpose assistant for platform inquiries',
            'category' => 'general',
            'variables' => ['user_name', 'query_type', 'service_info'],
            'example_response' => 'Hello {user_name}! I\'m here to help with {query_type}. Our platform offers: {service_info}',
            'priority' => 5,
            'is_active' => true
        ],
        [
            'name' => 'Emergency Response Assistant',
            'prompt' => 'You are an emergency response assistant. For medical emergencies, immediately direct users to call emergency services (108 in India) or visit the nearest hospital. Provide calm, clear instructions and emergency contact information. Variables: {user_location}, {emergency_type}',
            'description' => 'Handles emergency situations and provides immediate guidance',
            'category' => 'emergency',
            'variables' => ['user_location', 'emergency_type'],
            'example_response' => 'This sounds like a medical emergency. Please call 108 immediately or visit the nearest hospital. Stay calm and follow emergency protocols.',
            'priority' => 15,
            'is_active' => true
        ],
        [
            'name' => 'Doctor Onboarding Assistant',
            'prompt' => 'You are an assistant helping doctors join the FreeDoctor platform. Provide information about registration, verification process, setting up medical camps, and platform benefits. Variables: {doctor_name}, {specialization}, {registration_step}',
            'description' => 'Assists doctors with platform onboarding and registration',
            'category' => 'doctor_onboarding',
            'variables' => ['doctor_name', 'specialization', 'registration_step'],
            'example_response' => 'Welcome Dr. {doctor_name}! I\'m here to help you with your {specialization} practice setup. Current step: {registration_step}',
            'priority' => 8,
            'is_active' => true
        ]
    ];

    foreach ($prompts as $promptData) {
        $prompt = WhatsappChatGPTPrompt::create($promptData);
        echo "✅ Created prompt: {$prompt->name} (Category: {$prompt->category})\n";
    }

    echo "\n=== Summary ===\n";
    $total = WhatsappChatGPTPrompt::count();
    $active = WhatsappChatGPTPrompt::active()->count();
    echo "Total prompts: $total\n";
    echo "Active prompts: $active\n";

    echo "\n=== Prompts by Category ===\n";
    $categories = WhatsappChatGPTPrompt::select('category')
        ->groupBy('category')
        ->pluck('category');
    
    foreach ($categories as $category) {
        $count = WhatsappChatGPTPrompt::where('category', $category)->count();
        echo "- $category: $count prompts\n";
    }

    echo "\n✅ ChatGPT prompts setup completed!\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
?>
