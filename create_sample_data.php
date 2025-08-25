<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Create sample system health data
    App\Models\WhatsappSystemHealth::create([
        'component_name' => 'ai', 
        'status' => 'online', 
        'health_percentage' => 95.5, 
        'requests_today' => 342, 
        'avg_response_time' => 1.245, 
        'success_rate' => 97.3, 
        'last_check_at' => now()
    ]);

    App\Models\WhatsappSystemHealth::create([
        'component_name' => 'template', 
        'status' => 'online', 
        'health_percentage' => 98.2, 
        'requests_today' => 1247, 
        'avg_response_time' => 0.823, 
        'success_rate' => 99.1, 
        'last_check_at' => now()
    ]);

    App\Models\WhatsappSystemHealth::create([
        'component_name' => 'datatable', 
        'status' => 'online', 
        'health_percentage' => 92.8, 
        'requests_today' => 567, 
        'avg_response_time' => 0.045, 
        'success_rate' => 98.5, 
        'last_check_at' => now()
    ]);

    App\Models\WhatsappSystemHealth::create([
        'component_name' => 'function', 
        'status' => 'online', 
        'health_percentage' => 97.1, 
        'requests_today' => 234, 
        'avg_response_time' => 0.512, 
        'success_rate' => 96.2, 
        'last_check_at' => now()
    ]);

    App\Models\WhatsappSystemHealth::create([
        'component_name' => 'visualization', 
        'status' => 'maintenance', 
        'health_percentage' => 78.4, 
        'requests_today' => 89, 
        'avg_response_time' => 2.145, 
        'success_rate' => 78.9, 
        'last_check_at' => now()
    ]);

    // Create sample automation rules
    App\Models\WhatsappAutomationRule::create([
        'name' => 'Greeting Response',
        'description' => 'Automatically respond to greetings',
        'trigger_conditions' => ['keywords' => ['hi', 'hello', 'good morning', 'namaste']],
        'actions' => ['response' => 'Hello! Welcome to FreeDoctor. How can I help you today?'],
        'priority' => 'high',
        'is_active' => true
    ]);

    App\Models\WhatsappAutomationRule::create([
        'name' => 'Emergency Detection',
        'description' => 'Detect emergency keywords',
        'trigger_conditions' => ['keywords' => ['emergency', 'urgent', 'help', 'critical']],
        'actions' => ['response' => '🚨 Emergency detected. Please call 102 for immediate assistance.'],
        'priority' => 'high',
        'is_active' => true
    ]);

    App\Models\WhatsappAutomationRule::create([
        'name' => 'Doctor Search Helper',
        'description' => 'Help users find doctors',
        'trigger_conditions' => ['keywords' => ['find doctor', 'search doctor', 'doctor near me']],
        'actions' => ['response' => 'I can help you find doctors. Please share your location and specialty needed.'],
        'priority' => 'medium',
        'is_active' => true
    ]);

    // Create sample AI analysis data
    App\Models\WhatsappAiAnalysis::create([
        'conversation_id' => null,
        'message_id' => null,
        'analysis_type' => 'intent',
        'analysis_result' => 'doctor_search',
        'confidence_score' => 92.5,
        'ai_notes' => 'User wants to find a doctor based on message analysis',
        'ai_model_used' => 'gpt-4'
    ]);

    App\Models\WhatsappAiAnalysis::create([
        'conversation_id' => null,
        'message_id' => null,
        'analysis_type' => 'sentiment',
        'analysis_result' => 'positive',
        'confidence_score' => 85.3,
        'ai_notes' => 'User sentiment appears positive and engaged',
        'ai_model_used' => 'gpt-4'
    ]);

    App\Models\WhatsappAiAnalysis::create([
        'conversation_id' => null,
        'message_id' => null,
        'analysis_type' => 'intent',
        'analysis_result' => 'appointment_booking',
        'confidence_score' => 88.7,
        'ai_notes' => 'User wants to book an appointment',
        'ai_model_used' => 'gpt-4'
    ]);

    echo "Sample data created successfully!\n";
    echo "System health data: 5 components\n";
    echo "Automation rules: 3 rules\n";
    echo "AI analysis: 3 analysis records\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
