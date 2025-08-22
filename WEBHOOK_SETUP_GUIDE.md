# WhatsApp Webhook Setup Guide

## Current Status ✅
- Laravel app running on: http://127.0.0.1:8000
- Webhook endpoint working locally: ✅ VERIFIED
- Configuration files: ✅ PROPERLY CONFIGURED

## Quick Setup Steps

### Step 1: Start ngrok tunnel
1. Open a new PowerShell window
2. Run: `C:\ngrok\ngrok.exe http 8000`
3. Copy the HTTPS URL (looks like: https://abc123.ngrok.io)

### Step 2: Update webhook URL
1. Copy the ngrok HTTPS URL from step 1
2. Update your .env file:
   ```
   WHATSAPP_WEBHOOK_URL=https://YOUR_NGROK_URL.ngrok.io/api/webhook/whatsapp
   ```

### Step 3: Configure Meta Business Manager
1. Go to: https://business.facebook.com
2. Navigate to: WhatsApp Business API → Configuration → Webhooks
3. Add webhook:
   - **Callback URL**: `https://YOUR_NGROK_URL.ngrok.io/api/webhook/whatsapp`
   - **Verify Token**: `FreeDoctor2025SecureToken`
4. Subscribe to events: `messages`, `message_deliveries`, `message_reads`

### Step 4: Test the webhook
Run the test script:
```bash
php test_webhook_verification.php
```

## Troubleshooting

### If ngrok requires authentication:
1. Sign up at: https://ngrok.com/signup
2. Get your authtoken from dashboard
3. Run: `C:\ngrok\ngrok.exe config add-authtoken YOUR_TOKEN`
4. Then run: `C:\ngrok\ngrok.exe http 8000`

### Alternative: Use localtunnel
If ngrok doesn't work, try localtunnel:
```bash
npm install -g localtunnel
lt --port 8000
```

## Current Configuration
- **Verify Token**: FreeDoctor2025SecureToken
- **Phone Number ID**: 745838968612692
- **Business Account ID**: 1588252012149592
- **Local Webhook**: http://127.0.0.1:8000/api/webhook/whatsapp ✅

## What happens after setup:
1. Users send messages to your WhatsApp Business number
2. Meta sends webhook to your ngrok URL
3. Laravel processes the message
4. Auto-replies are sent back to users
5. Conversations are stored in database

---
**Note**: Keep both Laravel server and ngrok running while testing!
