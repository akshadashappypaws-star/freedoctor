🚀 **ALL WEBHOOK TEST URLS - READY TO COPY**
=====================================================

## 🌐 **PRODUCTION WEBHOOK ENDPOINTS**

### 📱 **WhatsApp Business API**
```
https://freedoctor.in/webhook/whatsapp
```
**Purpose:** Receive WhatsApp messages from customers
**Events:** New messages, message delivery status, read receipts
**Configuration:** Meta Business Manager → Webhooks

### 💰 **Razorpay Payment System**
```
https://freedoctor.in/webhook/razorpay
```
**Purpose:** Handle all Razorpay payment events
**Events:** Payment captured, failed, refunds, payouts
**Configuration:** Razorpay Dashboard → Webhooks

### 💳 **General Payment Gateway**
```
https://freedoctor.in/webhook/payment
```
**Purpose:** Handle other payment provider webhooks
**Events:** Generic payment events, transaction updates
**Configuration:** Your payment provider dashboard

### 🔧 **General Purpose Webhook**
```
https://freedoctor.in/webhook/general
```
**Purpose:** Catch-all for custom integrations
**Events:** API callbacks, third-party services
**Configuration:** Custom integrations

### 🧪 **Test & Debug Endpoint**
```
https://freedoctor.in/webhook/test
```
**Purpose:** Testing and development
**Events:** Test events only
**Configuration:** Development testing

---

## 🧪 **MANUAL TEST URLS**

### ✅ **WhatsApp Verification Test**
Copy and paste this URL in browser:
```
https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123
```
**Expected Response:** `test123`

### ✅ **Test Endpoint Health Check**
```
https://freedoctor.in/webhook/test
```
**Expected Response:** JSON success message

---

## 📊 **MONITORING & ADMIN URLS**

### 📈 **Webhook Monitor Dashboard**
```
https://freedoctor.in/webhook/monitor
```
**Purpose:** View all webhook activity, logs, statistics

### 📋 **Webhook Logs API**
```
https://freedoctor.in/webhook/logs
```
**Purpose:** JSON API for webhook logs

### 🔄 **Clear Webhook Logs**
```
https://freedoctor.in/webhook/clear
```
**Purpose:** Clear webhook log history

### 🧪 **Test Webhook System**
```
https://freedoctor.in/webhook/test
```
**Purpose:** Admin webhook testing

---

## 🔧 **EXTERNAL SERVICE CONFIGURATION**

### 📱 **Meta Business Manager (WhatsApp)**
1. Go to: https://developers.facebook.com/
2. Select your app → WhatsApp → Configuration → Webhooks
3. **Callback URL:** `https://freedoctor.in/webhook/whatsapp`
4. **Verify Token:** `FreeDoctor2025SecureToken`
5. **Events:** messages, message_deliveries, message_reads

### 💰 **Razorpay Dashboard**
1. Go to: https://dashboard.razorpay.com/app/webhooks
2. **Webhook URL:** `https://freedoctor.in/webhook/razorpay`
3. **Events:** payment.captured, payment.failed, order.paid, payout.processed
4. **Secret:** Set in your .env file

---

## 🔬 **CURL TESTING COMMANDS**

### Test WhatsApp Verification
```bash
curl "https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123"
```

### Test WhatsApp Message
```bash
curl -X POST https://freedoctor.in/webhook/whatsapp \
-H "Content-Type: application/json" \
-d '{"object":"whatsapp_business_account","entry":[{"changes":[{"value":{"messages":[{"from":"919876543210","text":{"body":"Test message"}}]}}]}]}'
```

### Test Razorpay Payment
```bash
curl -X POST https://freedoctor.in/webhook/razorpay \
-H "Content-Type: application/json" \
-d '{"event":"payment.captured","payload":{"payment":{"entity":{"id":"pay_test123","amount":50000,"status":"captured"}}}}'
```

### Test General Webhook
```bash
curl -X POST https://freedoctor.in/webhook/general \
-H "Content-Type: application/json" \
-d '{"event":"test_event","data":{"message":"Test webhook"}}'
```

---

## 📋 **WEBHOOK EVENT PURPOSES**

### 📱 **WhatsApp Events:**
- **New Message** → Customer sends WhatsApp message
- **Message Delivered** → Message successfully delivered
- **Message Read** → Customer read your message
- **Message Failed** → Message delivery failed

### 💰 **Payment Events:**
- **Payment Captured** → Money successfully received
- **Payment Failed** → Payment attempt failed
- **Order Paid** → Complete order payment received
- **Payout Processed** → Money sent to doctor/vendor

### 🔧 **System Events:**
- **User Registered** → New user account created
- **Campaign Created** → New medical camp created
- **Appointment Booked** → Doctor appointment scheduled
- **Notification Sent** → System notification delivered

---

## 🚀 **QUICK SETUP CHECKLIST**

✅ **Copy webhook URLs above**
✅ **Configure in external services**
✅ **Test with provided commands**
✅ **Monitor at:** `https://freedoctor.in/webhook/monitor`
✅ **Check logs if issues occur**

---

## ⚡ **INSTANT TEST COMMAND**

Run this to test ALL webhooks at once:
```bash
php test_all_webhooks.php
```

**🎯 All webhooks are configured to capture ANY activity on your platform!**

**📋 COPY THESE URLS TO YOUR EXTERNAL SERVICES:**
- WhatsApp: `https://freedoctor.in/webhook/whatsapp`
- Razorpay: `https://freedoctor.in/webhook/razorpay`
- Payment: `https://freedoctor.in/webhook/payment`
- General: `https://freedoctor.in/webhook/general`
- Monitor: `https://freedoctor.in/webhook/monitor`
