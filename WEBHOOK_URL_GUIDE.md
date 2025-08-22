# 🌐 GET NEW WEBHOOK URL - COMPLETE GUIDE

## Current Status: Ngrok Not Running

### 🚀 STEP 1: Start Ngrok
1. Open a **new terminal/command prompt**
2. Run this command:
```bash
ngrok http 8000
```
3. Keep this terminal open (ngrok must stay running)

### 🚀 STEP 2: Start Laravel Server (if not running)
In another terminal, run:
```bash
cd c:\xampp\htdocs\freedoctor-web
php artisan serve --port=8000
```

### 🚀 STEP 3: Get Webhook URL
After starting ngrok:
1. **Option A**: Visit http://localhost:4040 in your browser
2. **Option B**: Run this script again: `php get_webhook_url.php`
3. **Option C**: Look at the ngrok terminal - it shows the URL

### 🔗 STEP 4: Configure Meta Business Manager
The webhook URL format will be:
```
https://[random-id].ngrok-free.app/api/webhook/whatsapp
```

**Settings for Meta:**
- **Webhook URL**: `https://your-ngrok-url.ngrok-free.app/api/webhook/whatsapp`
- **Verify Token**: `freedoctor_webhook_token`
- **Webhook Fields**: `messages`

### 🎯 STEP 5: Test
1. Update Meta Business Manager with new webhook URL
2. Send a message from your phone to WhatsApp Business number
3. Check if message appears in your dashboard

## 🔄 Quick Commands

**Start everything:**
```bash
# Terminal 1: Start Laravel
cd c:\xampp\htdocs\freedoctor-web
php artisan serve --port=8000

# Terminal 2: Start Ngrok  
ngrok http 8000

# Terminal 3: Get webhook URL
cd c:\xampp\htdocs\freedoctor-web
php get_webhook_url.php
```

## 🛠️ Troubleshooting

**If ngrok shows "command not found":**
1. Download ngrok from https://ngrok.com/download
2. Extract to a folder (e.g., C:\ngrok)
3. Add C:\ngrok to your system PATH
4. Restart command prompt

**Database Connection Error:**
The error you saw might be related to production database settings. For local development, make sure your `.env` file has correct local database settings.

---
**Remember**: Each time you restart ngrok, you get a new URL and must update Meta Business Manager!
