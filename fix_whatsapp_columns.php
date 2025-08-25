<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Load Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking and fixing WhatsApp columns...\n";

try {
    // Check if is_automated column exists in whatsapp_messages table
    if (!Schema::hasColumn('whatsapp_messages', 'is_automated')) {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->boolean('is_automated')->default(false);
        });
        echo "✅ Added 'is_automated' column to whatsapp_messages table\n";
    } else {
        echo "✅ 'is_automated' column already exists in whatsapp_messages table\n";
    }

    // Check if template_used column exists
    if (!Schema::hasColumn('whatsapp_messages', 'template_used')) {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->string('template_used')->nullable();
        });
        echo "✅ Added 'template_used' column to whatsapp_messages table\n";
    } else {
        echo "✅ 'template_used' column already exists in whatsapp_messages table\n";
    }

    // Check if sent_at column exists
    if (!Schema::hasColumn('whatsapp_messages', 'sent_at')) {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->timestamp('sent_at')->nullable();
        });
        echo "✅ Added 'sent_at' column to whatsapp_messages table\n";
    } else {
        echo "✅ 'sent_at' column already exists in whatsapp_messages table\n";
    }

    echo "\n🎉 All required columns have been checked and added if necessary!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
