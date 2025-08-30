<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Create welcome template
    $template1 = new \App\Models\WhatsappTemplate();
    $template1->name = 'welcome_message';
    $template1->language = 'en_US';
    $template1->category = 'UTILITY';
    $template1->status = 'APPROVED';
    $template1->components = json_encode([
        ['type' => 'BODY', 'text' => 'Welcome to FreeDoctor! We are here to help you with your healthcare needs. How can we assist you today?']
    ]);
    $template1->save();
    echo "✅ Welcome template created\n";

    // Create appointment reminder template
    $template2 = new \App\Models\WhatsappTemplate();
    $template2->name = 'appointment_reminder';
    $template2->language = 'en_US';
    $template2->category = 'UTILITY';
    $template2->status = 'APPROVED';
    $template2->components = json_encode([
        ['type' => 'BODY', 'text' => 'Hi {{1}}, your appointment with Dr. {{2}} is scheduled for {{3}}. Please confirm your attendance by replying YES.']
    ]);
    $template2->save();
    echo "✅ Appointment reminder template created\n";

    // Create support template
    $template3 = new \App\Models\WhatsappTemplate();
    $template3->name = 'support_response';
    $template3->language = 'en_US';
    $template3->category = 'UTILITY';
    $template3->status = 'APPROVED';
    $template3->components = json_encode([
        ['type' => 'BODY', 'text' => 'Thank you for contacting FreeDoctor support. Our team will get back to you within 24 hours. For urgent matters, please call our helpline.']
    ]);
    $template3->save();
    echo "✅ Support response template created\n";

    // Create greeting template
    $template4 = new \App\Models\WhatsappTemplate();
    $template4->name = 'greeting_template';
    $template4->language = 'en_US';
    $template4->category = 'MARKETING';
    $template4->status = 'APPROVED';
    $template4->components = json_encode([
        ['type' => 'BODY', 'text' => 'Hello {{1}}! Good {{2}}! How are you feeling today? Do you need any medical assistance?']
    ]);
    $template4->save();
    echo "✅ Greeting template created\n";

    echo "\n🎉 All sample WhatsApp templates created successfully!\n";
    echo "Total templates: " . \App\Models\WhatsappTemplate::count() . "\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
