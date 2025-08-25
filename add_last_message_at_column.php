<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Check if column exists
    if (!Schema::hasColumn('whatsapp_conversations', 'last_message_at')) {
        DB::statement('ALTER TABLE whatsapp_conversations ADD COLUMN last_message_at TIMESTAMP NULL AFTER updated_at');
        DB::statement('ALTER TABLE whatsapp_conversations ADD INDEX idx_last_message_at (last_message_at)');
        echo "✅ Successfully added last_message_at column\n";
    } else {
        echo "ℹ️  Column last_message_at already exists\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
