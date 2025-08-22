# WhatsApp Webhook Debugging Guide

## ✅ Current Status
- **Webhook Controller**: Working correctly ✅
- **Database**: Saving messages properly ✅  
- **Auto-replies**: Configured and working ✅
- **Laravel Server**: Running on port 8000 ✅
- **Ngrok**: Running and exposing port 8000 ✅

## ❌ Issue Identified
Messages from your phone are NOT reaching the webhook. The webhook is working perfectly when tested locally.

## 🔧 Steps to Fix

### 1. Get Current Ngrok URL
- Open: http://localhost:4040
- Copy the HTTPS URL (something like: https://abc123.ngrok-free.app)

### 2. Update WhatsApp Webhook URL
You need to configure your WhatsApp Business API webhook with:
- **Webhook URL**: `https://your-ngrok-url.ngrok-free.app/api/webhook/whatsapp`
- **Verify Token**: `freedoctor_webhook_token`

### 3. Webhook Configuration Locations
The webhook needs to be configured in:
- **Meta Business Manager**: https://business.facebook.com
- **WhatsApp Business API Settings**
- **App Dashboard > WhatsApp > Configuration**

### 4. Test Webhook Verification
Visit this URL in browser to test verification:
```
https://your-ngrok-url.ngrok-free.app/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=freedoctor_webhook_token&hub.challenge=test123
```
Should return: `test123`

### 5. Test Live Messages
After configuring webhook URL:
1. Send a message to your WhatsApp Business number
2. Check Laravel logs for incoming webhook data
3. Check database for new messages

## 🚨 Common Issues

### A. Wrong Webhook URL
- Make sure you're using the HTTPS ngrok URL
- Include the full path: `/api/webhook/whatsapp`
- Don't use localhost or 127.0.0.1

### B. Verify Token Mismatch
- Webhook verify token must be: `freedoctor_webhook_token`
- Case sensitive and exact match required

### C. Ngrok Session Expired
- Free ngrok URLs change when restarted
- Update webhook URL whenever you restart ngrok

### D. WhatsApp Business Number Not Configured
- Messages must be sent to the correct WhatsApp Business number
- Check which number is configured in your WhatsApp Business API

## 📊 Testing Commands

### Check Recent Messages:
```bash
cd c:\xampp\htdocs\freedoctor-web
php check_conversations.php
```

### Test Webhook Locally:
```bash
cd c:\xampp\htdocs\freedoctor-web  
php test_webhook_direct.php
```

### Monitor Laravel Logs:
```bash
tail -f storage/logs/laravel.log
```

## 🎯 Next Steps

1. **Get ngrok URL** from http://localhost:4040
2. **Update webhook URL** in Meta Business Manager
3. **Test with real message** from your phone
4. **Check if message appears** in dashboard

The webhook system is working perfectly - we just need to make sure Meta/WhatsApp is sending messages to the correct URL!
