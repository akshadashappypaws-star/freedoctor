<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappSystemHealth;
use App\Models\WhatsappAutomationRule;
use App\Models\WhatsappAiAnalysis;
use App\Models\WhatsappUserBehavior;
use App\Models\WhatsappWeeklyReport;

class WhatsappAutomationSeeder extends Seeder
{
    public function run()
    {
        echo "Starting WhatsApp Automation seeding...\n";

        // First, create some conversations in the automation table for proper foreign key relationships
        $automationConversationsData = [
            [
                'phone_number' => '+91' . rand(7000000000, 9999999999),
                'contact_name' => 'John Doe',
                'user_type' => 'patient',
                'status' => 'active',
                'user_profile' => json_encode(['inquiry' => 'doctor_consultation', 'specialty' => 'general']),
                'last_message_at' => now()->subMinutes(rand(1, 60)),
                'created_at' => now()->subDays(rand(1, 7)),
                'updated_at' => now(),
            ],
            [
                'phone_number' => '+91' . rand(7000000000, 9999999999),
                'contact_name' => 'Jane Smith',
                'user_type' => 'patient',
                'status' => 'active',
                'user_profile' => json_encode(['inquiry' => 'emergency', 'urgency' => 'high']),
                'last_message_at' => now()->subMinutes(rand(1, 30)),
                'created_at' => now()->subDays(rand(1, 3)),
                'updated_at' => now(),
            ],
            [
                'phone_number' => '+91' . rand(7000000000, 9999999999),
                'contact_name' => 'Mike Johnson',
                'user_type' => 'patient',
                'status' => 'active',
                'user_profile' => json_encode(['inquiry' => 'doctor_search', 'specialty' => 'cardiology']),
                'last_message_at' => now()->subMinutes(rand(1, 120)),
                'created_at' => now()->subDays(rand(1, 5)),
                'updated_at' => now(),
            ]
        ];

        // Insert automation conversations
        foreach ($automationConversationsData as $conversation) {
            DB::table('whatsapp_conversations_automation')->insert($conversation);
        }

        // Get the inserted automation conversation IDs
        $automationConversationIds = DB::table('whatsapp_conversations_automation')
            ->whereIn('phone_number', array_column($automationConversationsData, 'phone_number'))
            ->pluck('id')
            ->toArray();

        echo "Added " . count($automationConversationsData) . " automation conversations\n";

        // Skip the regular whatsapp_conversations table for now since it doesn't exist

        // Continue with the rest of the automation data...
        // Create sample system health data
        WhatsappSystemHealth::create([
            'component_name' => 'ai',
            'status' => 'online',
            'health_percentage' => 95.5,
            'requests_today' => 342,
            'avg_response_time' => 1.245,
            'success_rate' => 97.3,
            'last_check_at' => now()
        ]);

        WhatsappSystemHealth::create([
            'component_name' => 'template',
            'status' => 'online',
            'health_percentage' => 98.2,
            'requests_today' => 1247,
            'avg_response_time' => 0.823,
            'success_rate' => 99.1,
            'last_check_at' => now()
        ]);

        WhatsappSystemHealth::create([
            'component_name' => 'datatable',
            'status' => 'online',
            'health_percentage' => 92.8,
            'requests_today' => 567,
            'avg_response_time' => 0.045,
            'success_rate' => 98.5,
            'last_check_at' => now()
        ]);

        WhatsappSystemHealth::create([
            'component_name' => 'function',
            'status' => 'online',
            'health_percentage' => 97.1,
            'requests_today' => 234,
            'avg_response_time' => 0.512,
            'success_rate' => 96.2,
            'last_check_at' => now()
        ]);

        WhatsappSystemHealth::create([
            'component_name' => 'visualization',
            'status' => 'maintenance',
            'health_percentage' => 78.4,
            'requests_today' => 89,
            'avg_response_time' => 2.145,
            'success_rate' => 78.9,
            'last_check_at' => now()
        ]);

        echo "Added 5 system health records\n";

        // Create sample automation rules
        WhatsappAutomationRule::create([
            'name' => 'Greeting Response',
            'description' => 'Automatically respond to greetings',
            'trigger_conditions' => json_encode(['keywords' => ['hi', 'hello', 'good morning', 'namaste']]),
            'actions' => json_encode(['response' => 'Hello! Welcome to FreeDoctor. How can I help you today?']),
            'priority' => 'high',
            'is_active' => true,
            'execution_count' => 145,
            'success_count' => 143
        ]);

        WhatsappAutomationRule::create([
            'name' => 'Emergency Detection',
            'description' => 'Detect emergency keywords',
            'trigger_conditions' => json_encode(['keywords' => ['emergency', 'urgent', 'help', 'critical']]),
            'actions' => json_encode(['response' => '🚨 Emergency detected. Please call 102 for immediate assistance.']),
            'priority' => 'high',
            'is_active' => true,
            'execution_count' => 23,
            'success_count' => 23
        ]);

        WhatsappAutomationRule::create([
            'name' => 'Doctor Search Helper',
            'description' => 'Help users find doctors',
            'trigger_conditions' => json_encode(['keywords' => ['find doctor', 'search doctor', 'doctor near me']]),
            'actions' => json_encode(['response' => 'I can help you find doctors. Please share your location and specialty needed.']),
            'priority' => 'medium',
            'is_active' => true,
            'execution_count' => 67,
            'success_count' => 62
        ]);

        echo "Added 3 automation rules\n";

        // Create sample AI analysis data with proper conversation references
        foreach ($automationConversationIds as $index => $conversationId) {
            WhatsappAiAnalysis::create([
                'conversation_id' => $conversationId,
                'message_id' => null,
                'analysis_type' => ['intent', 'sentiment', 'urgency'][$index % 3],
                'analysis_result' => ['doctor_search', 'positive', 'emergency'][$index % 3],
                'confidence_score' => rand(80, 98) + (rand(0, 99) / 100),
                'ai_notes' => 'AI analyzed conversation and detected user intent with high confidence.',
                'ai_model_used' => 'gpt-4'
            ]);
        }

        echo "Added " . count($automationConversationIds) . " AI analysis records\n";

        // Create sample user behavior data with proper conversation references
        foreach ($automationConversationIds as $index => $conversationId) {
            WhatsappUserBehavior::create([
                'conversation_id' => $conversationId,
                'interest_level' => ['high', 'medium', 'low'][$index % 3],
                'response_pattern' => ['quick', 'delayed', 'irregular'][$index % 3],
                'engagement_type' => ['interested', 'average', 'not_interested'][$index % 3],
                'total_messages' => rand(1, 10),
                'questions_asked' => rand(0, 5),
                'appointments_requested' => rand(0, 2),
                'avg_response_time' => rand(60, 600) + (rand(0, 99) / 100),
                'interaction_history' => json_encode(['user_behavior_tracked' => true]),
                'last_analyzed_at' => now()->subMinutes(rand(1, 1440))
            ]);
        }

        echo "Added " . count($automationConversationIds) . " user behavior records\n";

        // Create sample weekly report
        WhatsappWeeklyReport::create([
            'week_start' => now()->startOfWeek()->toDateString(),
            'week_end' => now()->endOfWeek()->toDateString(),
            'total_conversations' => 248,
            'new_conversations' => 45,
            'total_messages' => 1524,
            'automated_messages' => 856,
            'user_categorization' => json_encode(['interested' => 85, 'average' => 132, 'not_interested' => 31]),
            'popular_keywords' => json_encode(['doctor' => 145, 'appointment' => 89, 'emergency' => 23, 'location' => 67]),
            'ai_insights' => json_encode(['top_intent' => 'doctor_search', 'avg_confidence' => 87.3, 'sentiment_positive' => 68]),
            'automation_efficiency' => 94.5,
            'recommendations' => json_encode(['improve_emergency_response', 'add_more_greeting_variants', 'optimize_doctor_search_flow'])
        ]);

        echo "Added 1 weekly report\n";

        echo "\n✅ WhatsApp Automation sample data seeded successfully!\n";
        echo "📊 Summary:\n";
        echo "- Automation conversations: " . count($automationConversationsData) . "\n";
        echo "- System health records: 5\n";
        echo "- Automation rules: 3\n";
        echo "- AI analysis records: " . count($automationConversationIds) . "\n";
        echo "- User behavior records: " . count($automationConversationIds) . "\n";
        echo "- Weekly reports: 1\n";
        echo "\n🎯 All data is now dynamic and connected!\n";
    }
}
