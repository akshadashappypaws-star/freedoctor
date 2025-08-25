# 🚀 FreeDoctorCORPO - Hostinger Deployment Guide

## 🎯 Direct Git Deployment (Recommended)

### Step 1: SSH into Your Hostinger Server

```bash
ssh u150415685@in-mum-web1823.main-hosting.eu
```

### Step 2: Deploy from GitHub

```bash
# Navigate to your domain directory
cd /home/u150415685/domains/freedoctor.in/public_html

# Remove any existing files (backup first if needed)
rm -rf * .*

# Clone your repository
git clone https://github.com/Mytechdata/freedoctor-development.git .

# Install dependencies
composer install --no-dev --optimize-autoloader

# Setup Laravel
cp .env.example .env
php artisan key:generate --force
chmod -R 755 storage bootstrap/cache
```

## 📁 Alternative: File Upload Method

### Step 1: Upload Files to Hostinger

**Option A: Using File Manager (Recommended)**
1. Login to your Hostinger control panel
2. Go to **File Manager**
3. Navigate to `public_html/` directory
4. Upload `freedoctor-hostinger-deployment.zip`
5. Extract the zip file
6. Delete the zip file after extraction

**Option B: Using FTP**
1. Use your FTP client (FileZilla, WinSCP, etc.)
2. Connect with your Hostinger FTP credentials
3. Upload all files to `public_html/`

### Step 2: Database Setup

1. **Create Database in Hostinger:**
   - Go to **Databases** → **MySQL Databases**
   - Create database: `u[user_id]_freedoctor`
   - Create database user with full permissions
   - Note down: database name, username, password

2. **Update .env File:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://freedoctor.in

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u[your_user_id]_freedoctor
   DB_USERNAME=u[your_user_id]_dbuser
   DB_PASSWORD=your_database_password
   ```

### Step 3: Laravel Setup

1. **SSH into Hostinger** (if available) or use Terminal in File Manager:
   ```bash
   cd public_html
   php artisan key:generate
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   ```

2. **Set Permissions:**
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   ```

### Step 4: Domain Configuration

1. **Document Root:** Set to `public_html/public` in Hostinger panel
2. **Create .htaccess in public_html:**
   ```apache
   RewriteEngine On
   RewriteRule ^(.*)$ public/$1 [L]
   ```

### Step 5: Update Webhook URLs

Update all external services to use your production domain:

**WhatsApp Business API:**
- Webhook URL: `https://freedoctor.in/webhook/whatsapp`

**Razorpay:**
- Webhook URL: `https://freedoctor.in/webhook/razorpay`

### Step 6: Test Deployment

1. **Check Application:**
   - Visit: `https://freedoctor.in`
   - Test login/registration

2. **Test Webhooks:**
   - WhatsApp: `https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123`
   - Should return: `test123`

## 🔧 Production Environment Variables

```env
APP_NAME=FreeDoctorCORPO
APP_ENV=production
APP_DEBUG=false
APP_URL=https://freedoctor.in

# Database (Update with your Hostinger details)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u[user_id]_freedoctor
DB_USERNAME=u[user_id]_dbuser
DB_PASSWORD=your_password

# WhatsApp Production URLs
WHATSAPP_WEBHOOK_URL=https://freedoctor.in/webhook/whatsapp
WHATSAPP_WEBHOOK_VERIFY_TOKEN=FreeDoctor2025SecureToken

# All other settings remain the same as in .env
```

## 🛡️ Security Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Use strong database passwords
- [ ] Enable HTTPS in Hostinger
- [ ] Set proper file permissions
- [ ] Backup database before deployment

## 📱 Update External Services

After deployment, update these services with your production URLs:

1. **Meta Business Manager:**
   - Webhook: `https://freedoctor.in/webhook/whatsapp`
   - Verify Token: `FreeDoctor2025SecureToken`

2. **Razorpay Dashboard:**
   - Webhook: `https://freedoctor.in/webhook/razorpay`

3. **Google OAuth:**
   - Redirect URI: `https://freedoctor.in/auth/google/callback`

## 🚨 Troubleshooting

**Common Issues:**

1. **500 Error:** Check file permissions and .env configuration
2. **Database Error:** Verify database credentials in .env
3. **Webhook Issues:** Ensure URLs are accessible publicly

**Log Files:**
- Laravel logs: `storage/logs/laravel.log`
- Server logs: Check Hostinger control panel

---

## 📦 Files Ready for Deployment

✅ `freedoctor-hostinger-deployment.zip` - Production-ready package
✅ All webhook endpoints configured
✅ Production environment variables
✅ Database migrations ready
✅ WhatsApp integration ready

**Next:** Upload the zip file to Hostinger and follow the steps above!
