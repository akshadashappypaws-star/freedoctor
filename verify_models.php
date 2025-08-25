<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WhatsappSystemHealth;
use App\Models\WhatsappAutomationRule;
use App\Models\WhatsappAiAnalysis;
use App\Models\WhatsappUserBehavior;
use App\Models\WhatsappWeeklyReport;

echo "=== Verifying WhatsApp Automation Models ===\n\n";

// Check System Health
echo "System Health Records:\n";
$healthRecords = WhatsappSystemHealth::all();
foreach ($healthRecords as $health) {
    echo "- {$health->component_name}: {$health->status} ({$health->health_percentage}%)\n";
}

echo "\nAutomation Rules:\n";
$rules = WhatsappAutomationRule::all();
foreach ($rules as $rule) {
    echo "- {$rule->name}: {$rule->priority} priority ({$rule->execution_count} executions)\n";
}

echo "\nAI Analysis Records:\n";
$analyses = WhatsappAiAnalysis::all();
foreach ($analyses as $analysis) {
    echo "- {$analysis->analysis_type}: {$analysis->analysis_result} ({$analysis->confidence_score}% confidence)\n";
}

echo "\nUser Behavior Records:\n";
$behaviors = WhatsappUserBehavior::all();
foreach ($behaviors as $behavior) {
    echo "- {$behavior->engagement_type}: {$behavior->interest_level} interest ({$behavior->total_messages} messages)\n";
}

echo "\nWeekly Reports:\n";
$reports = WhatsappWeeklyReport::all();
foreach ($reports as $report) {
    echo "- Week {$report->week_start} to {$report->week_end}: {$report->total_conversations} conversations\n";
}

echo "\n✅ All models are working correctly!\n";
