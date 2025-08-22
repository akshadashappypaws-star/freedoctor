php artisan migrate:status
php artisan config:clear
php artisan cache:clear

# Base WhatsApp tables
php artisan migrate --path=/database/migrations/2025_08_20_000000_create_whatsapp_templates_table.php
php artisan migrate --path=/database/migrations/2025_08_19_000000_create_whatsapp_auto_reply_tables.php
php artisan migrate --path=/database/migrations/2025_08_20_000001_create_whatsapp_bot_tables.php

# Enhancement migrations
php artisan migrate --path=/database/migrations/2025_08_20_000001_enhance_whatsapp_conversations_table.php
php artisan migrate --path=/database/migrations/2025_08_21_000000_create_whatsapp_bulk_messages_table.php
php artisan migrate --path=/database/migrations/2025_08_22_000000_create_whatsapp_chatgpt_prompts_table.php

# Add relationships and additional features
php artisan migrate --path=/database/migrations/2025_08_23_000000_add_relationships_to_whatsapp_conversations.php
php artisan migrate --path=/database/migrations/2025_08_20_000006_add_tracking_to_whatsapp_bulk_messages.php
php artisan migrate --path=/database/migrations/2025_08_20_000005_add_missing_whatsapp_bot_tables.php
php artisan migrate --path=/database/migrations/2025_08_20_000004_create_whatsapp_lead_management_tables.php
php artisan migrate --path=/database/migrations/2025_08_20_000003_add_delayed_response_columns_to_whatsapp_tables.php
php artisan migrate --path=/database/migrations/2025_08_20_000002_enhance_whatsapp_auto_replies_table.php
