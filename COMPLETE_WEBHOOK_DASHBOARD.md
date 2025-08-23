📋 **COMPLETE WEBHOOK TESTING DASHBOARD**
==============================================

## 🌐 **PRODUCTION WEBHOOK URLS**

### 📱 **WhatsApp Business API Webhook**
```
https://freedoctor.in/webhook/whatsapp
```
**Purpose:** Receive WhatsApp messages from customers, process auto-replies, handle message status updates
**Verification Token:** `FreeDoctor2025SecureToken`
**Events:** messages, message_deliveries, message_reads, message_echoes

### 💰 **Razorpay Payment Webhook**  
```
https://freedoctor.in/webhook/razorpay
```
**Purpose:** Handle payment events (captured, failed, refunded), payout processing, account validations
**Events:** payment.captured, payment.failed, order.paid, payout.processed, fund_account.validation.completed

### 💳 **General Payment Webhook**
```
https://freedoctor.in/webhook/payment
```
**Purpose:** Handle generic payment gateway events, transaction updates, payment confirmations
**Events:** payment.captured, payment.failed, payment.authorized, order.paid

### 🔧 **General Purpose Webhook**
```
https://freedoctor.in/webhook/general
```
**Purpose:** Catch-all webhook for custom integrations, third-party services, API callbacks
**Events:** Any custom event you configure

### 🧪 **Test Endpoint**
```
https://freedoctor.in/webhook/test
```
**Purpose:** Testing webhook functionality, debugging, development testing
**Events:** Test events only

---

## 🧪 **MANUAL TEST URLS** 

### ✅ **WhatsApp Verification Test**
```
https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123
```
**Expected Response:** `test123`
**Purpose:** Verify WhatsApp webhook is working before configuring in Meta

### ✅ **Test Webhook Endpoint**
```
https://freedoctor.in/webhook/test
```
**Expected Response:** JSON with success status
**Purpose:** Quick health check of webhook system

---

## 📊 **MONITORING & DEBUGGING URLS**

### 📈 **ngrok Dashboard** (if using ngrok)
```
http://127.0.0.1:4040
```
**Purpose:** Monitor incoming webhook requests, see request/response logs

### 📋 **Laravel Logs**
```
storage/logs/laravel.log
```
**Purpose:** View detailed webhook processing logs, error messages

### 🔍 **Admin Webhook Monitor** (if implemented)
```
https://freedoctor.in/webhook/monitor
```
**Purpose:** Admin dashboard for webhook statistics and logs

---

## 🔧 **CONFIGURATION URLS**

### 📱 **Meta for Developers Console**
```
https://developers.facebook.com/
```
**Configure:** WhatsApp webhook URL and verify token

### 💰 **Razorpay Dashboard**
```
https://dashboard.razorpay.com/app/webhooks
```
**Configure:** Payment webhook URLs and events

### 🔐 **Google Cloud Console** (if using Google services)
```
https://console.cloud.google.com/
```
**Configure:** API keys, OAuth settings

---

## 🧪 **CURL TEST COMMANDS**

### Test WhatsApp Verification
```bash
curl -X GET "https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123"
```

### Test WhatsApp Message Webhook
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

### Test Razorpay Webhook
```bash
curl -X POST https://freedoctor.in/webhook/razorpay \
-H "Content-Type: application/json" \
-H "X-Razorpay-Signature: your_signature_here" \
-d '{
  "entity": "event",
  "account_id": "acc_test123",
  "event": "payment.captured",
  "payload": {
    "payment": {
      "entity": {
        "id": "pay_test12345",
        "amount": 50000,
        "currency": "INR",
        "status": "captured"
      }
    }
  }
}'
```

### Test General Webhook
```bash
curl -X POST https://freedoctor.in/webhook/general \
-H "Content-Type: application/json" \
-d '{
  "event": "test_event",
  "data": {
    "message": "Test webhook",
    "timestamp": "2025-08-23T10:30:00Z"
  }
}'
```

---

## 📋 **WEBHOOK EVENT PURPOSES**

### 📱 **WhatsApp Events:**
- **messages** → New message received from customer
- **message_deliveries** → Message delivered to customer  
- **message_reads** → Customer read the message
- **message_echoes** → Sent message confirmation

### 💰 **Payment Events:**
- **payment.captured** → Payment successfully captured
- **payment.failed** → Payment failed
- **payment.authorized** → Payment authorized but not captured
- **order.paid** → Order fully paid
- **payout.processed** → Money sent to doctor/vendor
- **fund_account.validation.completed** → Bank account verified

### 🔧 **System Events:**
- **user.registered** → New user registration
- **campaign.created** → New medical camp created
- **appointment.booked** → Doctor appointment booked
- **notification.sent** → System notification delivered

---

## 🚀 **QUICK TEST SCRIPT**

Run this to test all webhooks at once:
```bash
php test_webhook_complete.php https://freedoctor.in
```

---

## ⚠️ **IMPORTANT NOTES:**

1. **Production URLs** use `https://freedoctor.in` (your live domain)
2. **All webhooks** are configured to capture ANY activity
3. **Logs are maintained** for all webhook calls
4. **Failed webhooks** are automatically retried
5. **Security** - All webhooks verify signatures when provided

**🎯 Copy the URLs above and configure them in your external services to start receiving webhook events!**
