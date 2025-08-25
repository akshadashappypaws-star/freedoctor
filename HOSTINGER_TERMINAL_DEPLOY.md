# 🚀 FreeDoctorCORPO - Hostinger Deployment via Control Panel

## Method 1: Using Hostinger Terminal (Recommended)

### Step 1: Access Hostinger Terminal
1. **Login to Hostinger Control Panel**
2. **Go to:** Advanced → Terminal (or File Manager → Terminal)
3. **If Terminal is not available:** Use File Manager and upload deployment script

### Step 2: Run Deployment Commands

**Copy and paste these commands one by one:**

```bash
# Navigate to your domain directory
cd /home/u150415685/domains/freedoctor.in/public_html

# Remove existing files (backup first if needed)
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

### Step 3: Configure Environment

**Edit the .env file:**
```bash
nano .env
```

**Update these key settings:**
```env
APP_NAME=FreeDoctorCORPO
APP_ENV=production
APP_DEBUG=false
APP_URL=https://freedoctor.in

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u150415685_freedoctor
DB_USERNAME=u150415685_user
DB_PASSWORD=YOUR_DB_PASSWORD

WHATSAPP_WEBHOOK_URL=https://freedoctor.in/webhook/whatsapp
```

### Step 4: Run Database Setup

```bash
# Run migrations
php artisan migrate --force

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Verify Deployment

**Test these URLs:**
- 🌐 **Main site:** https://freedoctor.in
- 📱 **Webhook test:** https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123

---

## Method 2: File Manager Upload (Alternative)

### If Terminal is not available:

1. **Download deployment package:** `freedoctor-clean.zip`
2. **Upload to Hostinger File Manager**
3. **Extract in public_html/**
4. **Use File Manager's built-in editor to modify .env**
5. **Run PHP commands via cPanel if available**

---

## 🔧 Troubleshooting SSH Issues

**Your SSH connection is timing out due to:**
- Network firewall restrictions
- ISP blocking SSH ports
- Hostinger may require specific SSH configuration

**Solutions:**
1. **Use Hostinger's built-in Terminal** (recommended)
2. **Contact your ISP** about SSH port 22 access
3. **Use VPN** to bypass restrictions
4. **Use Hostinger File Manager** with manual setup

---

## ✅ Success Checklist

- [ ] Repository cloned to public_html/
- [ ] .env file configured with database credentials
- [ ] Database migrations completed
- [ ] File permissions set correctly
- [ ] Website loads at https://freedoctor.in
- [ ] Webhook test returns "test123"

---

**Your FreeDoctorCORPO application with complete webhook system is ready for deployment! 🎉**
