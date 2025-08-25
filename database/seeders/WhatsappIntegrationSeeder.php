<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\WhatsappAiAnalysis;
use App\Models\WhatsappAutomationRule;
use App\Models\WhatsappUserBehavior;

class WhatsappIntegrationSeeder extends Seeder
{
    public function run()
    {
        echo "Starting WhatsApp Integration seeding...\n";

        // Add sample data to whatsapp_messages table
        $messageData = [
            [
                'message_id' => 'msg_' . uniqid(),
                'from_number' => '+917234567890',
                'to_number' => '+919876543210',
                'message_type' => 'text',
                'message_body' => 'Hello, I need a doctor consultation',
                'webhook_data' => json_encode(['timestamp' => time(), 'status' => 'received']),
                'status' => 'delivered',
                'direction' => 'inbound',
                'message_timestamp' => now()->subMinutes(30),
                'delivered_at' => now()->subMinutes(29),
                'read_at' => now()->subMinutes(28),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'message_id' => 'msg_' . uniqid(),
                'from_number' => '+919876543210',
                'to_number' => '+917234567890',
                'message_type' => 'template',
                'message_body' => 'Hello! I can help you find a doctor. What type of specialist are you looking for?',
                'webhook_data' => json_encode(['template_id' => 'doctor_greeting', 'automated' => true]),
                'status' => 'read',
                'direction' => 'outbound',
                'message_timestamp' => now()->subMinutes(27),
                'delivered_at' => now()->subMinutes(26),
                'read_at' => now()->subMinutes(25),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'message_id' => 'msg_' . uniqid(),
                'from_number' => '+918765432109',
                'to_number' => '+919876543210',
                'message_type' => 'text',
                'message_body' => 'Emergency! Need immediate help',
                'webhook_data' => json_encode(['urgency' => 'high', 'keywords' => ['emergency']]),
                'status' => 'delivered',
                'direction' => 'inbound',
                'message_timestamp' => now()->subMinutes(15),
                'delivered_at' => now()->subMinutes(14),
                'read_at' => now()->subMinutes(13),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($messageData as $message) {
            DB::table('whatsapp_messages')->insert($message);
        }

        echo "Added " . count($messageData) . " sample messages\n";

        // Add sample automation logs
        $automationRules = WhatsappAutomationRule::all();
        $logData = [];

        foreach ($automationRules as $rule) {
            $logData[] = [
                'automation_rule_id' => $rule->id,
                'phone_number' => '+917234567890',
                'trigger_message' => 'Hello, I need a doctor consultation',
                'response_sent' => 'Hello! I can help you find a doctor. What type of specialist are you looking for?',
                'execution_status' => 'success',
                'error_message' => null,
                'execution_time' => rand(100, 500) / 1000, // 0.1 to 0.5 seconds
                'context_data' => json_encode(['intent' => 'doctor_search', 'confidence' => 0.95]),
                'executed_at' => now()->subMinutes(rand(10, 60)),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        foreach ($logData as $log) {
            DB::table('whatsapp_automation_logs')->insert($log);
        }

        echo "Added " . count($logData) . " automation logs\n";

        // Update existing whatsapp_conversations with AI analysis links
        $conversations = DB::table('whatsapp_conversations')->limit(5)->get();
        $aiAnalyses = WhatsappAiAnalysis::all();
        $automationRules = WhatsappAutomationRule::all();
        $userBehaviors = WhatsappUserBehavior::all();

        foreach ($conversations as $index => $conversation) {
            $updateData = [];
            
            if (isset($aiAnalyses[$index % count($aiAnalyses)])) {
                $updateData['ai_analysis_id'] = $aiAnalyses[$index % count($aiAnalyses)]->id;
            }
            
            if (isset($automationRules[$index % count($automationRules)])) {
                $updateData['automation_rule_id'] = $automationRules[$index % count($automationRules)]->id;
            }
            
            if (isset($userBehaviors[$index % count($userBehaviors)])) {
                $updateData['user_behavior_id'] = $userBehaviors[$index % count($userBehaviors)]->id;
            }

            if (!empty($updateData)) {
                DB::table('whatsapp_conversations')
                    ->where('id', $conversation->id)
                    ->update($updateData);
            }
        }

        echo "Updated " . count($conversations) . " conversations with integration links\n";

        // Update existing whatsapp_lead_scores with AI analysis links
        $leadScores = DB::table('whatsapp_lead_scores')->limit(3)->get();
        
        foreach ($leadScores as $index => $leadScore) {
            $updateData = [];
            
            if (isset($aiAnalyses[$index % count($aiAnalyses)])) {
                $updateData['ai_analysis_id'] = $aiAnalyses[$index % count($aiAnalyses)]->id;
            }
            
            $updateData['automation_triggered'] = rand(0, 1);
            $updateData['behavior_score'] = rand(60, 95) + (rand(0, 99) / 100);

            DB::table('whatsapp_lead_scores')
                ->where('id', $leadScore->id)
                ->update($updateData);
        }

        echo "Updated " . count($leadScores) . " lead scores with integration data\n";

        echo "\n✅ WhatsApp Integration seeding completed successfully!\n";
        echo "📊 Summary:\n";
        echo "- Sample messages: " . count($messageData) . "\n";
        echo "- Automation logs: " . count($logData) . "\n";
        echo "- Updated conversations: " . count($conversations) . "\n";
        echo "- Updated lead scores: " . count($leadScores) . "\n";
        echo "\n🔗 All tables are now fully integrated!\n";
    }
}
