<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Load Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking and fixing WhatsApp Templates table...\n";

try {
    // Check if whatsapp_id column exists in whatsapp_templates table
    if (!Schema::hasColumn('whatsapp_templates', 'whatsapp_id')) {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->string('whatsapp_id')->nullable()->after('id');
        });
        echo "✅ Added 'whatsapp_id' column to whatsapp_templates table\n";
    } else {
        echo "✅ 'whatsapp_id' column already exists in whatsapp_templates table\n";
    }

    // Check if language column exists
    if (!Schema::hasColumn('whatsapp_templates', 'language')) {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->string('language')->default('en');
        });
        echo "✅ Added 'language' column to whatsapp_templates table\n";
    } else {
        echo "✅ 'language' column already exists in whatsapp_templates table\n";
    }

    // Check if category column exists
    if (!Schema::hasColumn('whatsapp_templates', 'category')) {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->string('category')->default('UTILITY');
        });
        echo "✅ Added 'category' column to whatsapp_templates table\n";
    } else {
        echo "✅ 'category' column already exists in whatsapp_templates table\n";
    }

    // Check if components column exists
    if (!Schema::hasColumn('whatsapp_templates', 'components')) {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->json('components')->nullable();
        });
        echo "✅ Added 'components' column to whatsapp_templates table\n";
    } else {
        echo "✅ 'components' column already exists in whatsapp_templates table\n";
    }

    // Check if status column exists
    if (!Schema::hasColumn('whatsapp_templates', 'status')) {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->string('status')->default('pending');
        });
        echo "✅ Added 'status' column to whatsapp_templates table\n";
    } else {
        echo "✅ 'status' column already exists in whatsapp_templates table\n";
    }

    echo "\n📋 WhatsApp Template Structure:\n";
    echo "- whatsapp_id: Official WhatsApp template ID\n";
    echo "- language: Template language (en, es, fr, etc.)\n";
    echo "- category: Template category (UTILITY, MARKETING, etc.)\n";
    echo "- components: JSON structure for template components\n";
    echo "- status: Template approval status (pending, approved, rejected)\n";

    echo "\n🎉 All WhatsApp template columns have been checked and added if necessary!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
