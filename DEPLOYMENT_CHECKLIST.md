✅ FreeDoctorCORPO - Hostinger Deployment Checklist

## 📦 Ready for Deployment
- [x] Clean deployment package created: `freedoctor-clean.zip` (29.59 MB)
- [x] All webhook endpoints ready
- [x] Production environment configuration
- [x] Database migrations ready
- [x] WhatsApp Business API integration
- [x] Razorpay payment integration
- [x] Google OAuth integration

## 🚀 Deployment Steps

### 1. Upload to Hostinger
- [ ] Login to Hostinger control panel
- [ ] Go to File Manager
- [ ] Upload `freedoctor-clean.zip` to `public_html/`
- [ ] Extract the zip file
- [ ] Delete zip file after extraction

### 2. Database Setup
- [ ] Create MySQL database in Hostinger
- [ ] Create database user with permissions
- [ ] Update `.env` file with database credentials

### 3. Laravel Configuration
- [ ] Set document root to `public_html/public`
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan migrate --force`
- [ ] Set proper file permissions (755 for storage)

### 4. Update External Services
- [ ] Meta Business Manager webhook: `https://freedoctor.in/webhook/whatsapp`
- [ ] Razorpay webhook: `https://freedoctor.in/webhook/razorpay`
- [ ] Google OAuth redirect: `https://freedoctor.in/auth/google/callback`

### 5. Test Everything
- [ ] Visit website: `https://freedoctor.in`
- [ ] Test webhook: `https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123`
- [ ] Test user registration/login
- [ ] Test WhatsApp message receiving

## 🔧 Production Environment Variables

```env
APP_NAME=FreeDoctorCORPO
APP_ENV=production
APP_DEBUG=false
APP_URL=https://freedoctor.in

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u[your_user_id]_freedoctor
DB_USERNAME=u[your_user_id]_user
DB_PASSWORD=your_password

WHATSAPP_WEBHOOK_URL=https://freedoctor.in/webhook/whatsapp
WHATSAPP_WEBHOOK_VERIFY_TOKEN=FreeDoctor2025SecureToken
```

## 📱 Webhook URLs for External Services

| Service | Webhook URL |
|---------|-------------|
| WhatsApp | `https://freedoctor.in/webhook/whatsapp` |
| Razorpay | `https://freedoctor.in/webhook/razorpay` |
| Payments | `https://freedoctor.in/webhook/payment` |
| General | `https://freedoctor.in/webhook/general` |

## 🛡️ Security Notes

- Set `APP_DEBUG=false` in production
- Use strong database passwords
- Enable HTTPS redirect in Hostinger
- Set proper file permissions (755/644)

---

**Status**: ✅ Ready for deployment!
**Package**: `freedoctor-clean.zip` (29.59 MB)
**Deployment Guide**: `HOSTINGER_QUICK_DEPLOY.md`
