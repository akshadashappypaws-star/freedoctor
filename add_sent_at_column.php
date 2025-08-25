<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Check if column exists
    if (!Schema::hasColumn('whatsapp_messages', 'sent_at')) {
        DB::statement('ALTER TABLE whatsapp_messages ADD COLUMN sent_at TIMESTAMP NULL AFTER message_body');
        DB::statement('ALTER TABLE whatsapp_messages ADD INDEX idx_sent_at (sent_at)');
        echo "✅ Successfully added sent_at column to whatsapp_messages\n";
    } else {
        echo "ℹ️  Column sent_at already exists in whatsapp_messages\n";
    }
    
    // Update existing records to set sent_at = message_timestamp if sent_at is null
    $updated = DB::update('UPDATE whatsapp_messages SET sent_at = message_timestamp WHERE sent_at IS NULL AND message_timestamp IS NOT NULL');
    echo "ℹ️  Updated {$updated} records to set sent_at from message_timestamp\n";
    
    // Update records where message_timestamp is null, use created_at
    $updated2 = DB::update('UPDATE whatsapp_messages SET sent_at = created_at WHERE sent_at IS NULL');
    echo "ℹ️  Updated {$updated2} additional records to set sent_at from created_at\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
