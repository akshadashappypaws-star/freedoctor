🔥 **WHATSAPP WEBHOOK - READY TO USE**

## ✅ Your ngrok tunnel is LIVE:
```
https://freedoctor.in
```

## 📱 **WHATSAPP BUSINESS API WEBHOOK URL:**
```
https://freedoctor.in/webhook/whatsapp
```

## 🔧 **CONFIGURATION FOR META BUSINESS MANAGER:**

### Step 1: Go to Meta for Developers
URL: https://developers.facebook.com/

### Step 2: Navigate to WhatsApp
- Select your app
- Go to WhatsApp → Configuration → Webhooks

### Step 3: Add Webhook
**Callback URL:** 
```
https://freedoctor.in/webhook/whatsapp
```

**Verify Token:** 
```
FreeDoctor2025SecureToken
```

### Step 4: Subscribe to Events
Select these events:
- ✅ messages
- ✅ message_deliveries  
- ✅ message_reads
- ✅ message_echoes

## 🧪 **TEST YOUR WEBHOOK:**

### Quick Test Command:
```bash
curl -X GET "https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123"
```

**Expected Response:** `test123`

### Send Test Message:
```bash
curl -X POST https://freedoctor.in/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{
  "object": "whatsapp_business_account",
  "entry": [{
    "id": "102290129340398",
    "changes": [{
      "value": {
        "messaging_product": "whatsapp",
        "metadata": {
          "display_phone_number": "917741044366",
          "phone_number_id": "745838968612692"
        },
        "contacts": [{
          "profile": {"name": "Test User"},
          "wa_id": "919876543210"
        }],
        "messages": [{
          "from": "919876543210",
          "id": "wamid.test12345",
          "timestamp": "1692797400",
          "text": {"body": "Hello Doctor"},
          "type": "text"
        }]
      },
      "field": "messages"
    }]
  }]
}'
```

## ⚠️ **IMPORTANT NOTES:**

1. **Keep ngrok running** - Don't close the terminal window
2. **Laravel server must be running** on port 8000
3. **URL expires** when you restart ngrok (get new URL)
4. **Free ngrok** shows warning page - click "Visit Site" to continue

## 📊 **MONITORING:**

- **ngrok Dashboard:** http://127.0.0.1:4040
- **Live Logs:** Check Laravel logs for incoming webhooks
- **Test Endpoint:** https://freedoctor.in/webhook/test

## 🔄 **WEBHOOK VERIFICATION PROCESS:**

1. Meta sends GET request with verification challenge
2. Your webhook responds with the challenge
3. Meta confirms and activates webhook
4. Ready to receive WhatsApp messages!

---

**🚀 COPY THE WEBHOOK URL ABOVE AND PASTE IT IN META BUSINESS MANAGER!**

**Your WhatsApp Webhook URL:**
```
https://freedoctor.in/webhook/whatsapp
```
