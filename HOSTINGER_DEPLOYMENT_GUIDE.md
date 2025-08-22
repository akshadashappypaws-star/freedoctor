# FreeDoctor Web - Hostinger GitHub Deployment Guide

## Prerequisites
- GitHub account with SSH key added
- Hostinger hosting account with SSH access
- Domain pointed to Hostinger

## Step 1: GitHub Repository Setup

1. **Create Repository:**
   ```bash
   # On GitHub.com, create new repository: freedoctor-web
   # Set as private, don't initialize with README
   ```

2. **Add SSH Key to GitHub:**
   - Go to GitHub Settings → SSH and GPG keys
   - Add the provided SSH key from Hostinger

## Step 2: Connect Local Repository

```bash
# Add GitHub remote (replace YOUR_USERNAME with your actual GitHub username)
git remote add origin git@github.com:YOUR_USERNAME/freedoctor-web.git

# Push existing code to GitHub
git branch -M main
git push -u origin main
```

## Step 3: Hostinger Server Setup

### 3.1 SSH into Hostinger
```bash
ssh u150415685@in-mum-web1823.main-hosting.eu
```

### 3.2 Navigate to Domain Directory
```bash
cd /home/u150415685/domains/your-domain.com/public_html
```

### 3.3 Clone Repository
```bash
# Remove any existing files (backup first if needed)
rm -rf * .htaccess .env

# Clone your GitHub repository
git clone git@github.com:YOUR_USERNAME/freedoctor-web.git .
```

### 3.4 Laravel Configuration on Hostinger

```bash
# Copy environment file
cp .env.example .env

# Edit environment variables
nano .env
```

**Important .env settings for Hostinger:**
```env
APP_NAME=FreeDoctor
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u150415685_freedoctor
DB_USERNAME=u150415685_freedoctor
DB_PASSWORD=your_database_password

# WhatsApp Configuration
WHATSAPP_PHONE_NUMBER_ID=745838968612692
WHATSAPP_ACCESS_TOKEN=your_production_token
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_verify_token
WHATSAPP_VERSION=v20.0

# Set production webhook URL
WHATSAPP_WEBHOOK_URL=https://your-domain.com/api/webhook/whatsapp
```

### 3.5 Install Dependencies and Setup
```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build
npm install --production
npm run build

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Setup proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step 4: Domain Configuration

### 4.1 Update Document Root
In Hostinger control panel:
- Go to Advanced → Subdomains/Domains
- Set document root to: `/domains/your-domain.com/public_html/public`

### 4.2 Create .htaccess in public_html
```apache
# Redirect all requests to Laravel's public directory
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
```

## Step 5: WhatsApp Webhook Update

Update your Meta Business Manager webhook URL to:
```
https://your-domain.com/api/webhook/whatsapp
```

## Step 6: Automated Deployment Script

Make the deploy script executable:
```bash
chmod +x deploy.sh
```

### Future Deployments
```bash
# On your Hostinger server, just run:
./deploy.sh
```

## Step 7: SSL Certificate

1. **Enable SSL in Hostinger:**
   - Go to Security → SSL/TLS
   - Enable "Force HTTPS redirect"

2. **Update Laravel to force HTTPS:**
   ```php
   // In app/Providers/AppServiceProvider.php boot() method
   if (config('app.env') === 'production') {
       \URL::forceScheme('https');
   }
   ```

## Troubleshooting

### Common Issues:

1. **Composer Memory Issues:**
   ```bash
   php -d memory_limit=-1 /usr/local/bin/composer install
   ```

2. **File Permissions:**
   ```bash
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   chmod -R 775 storage bootstrap/cache
   ```

3. **Database Connection:**
   ```bash
   # Test database connection
   php artisan migrate:status
   ```

4. **Clear All Caches:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

## Security Checklist

- [ ] Set APP_DEBUG=false in production
- [ ] Use strong database passwords
- [ ] Enable HTTPS redirect
- [ ] Set proper file permissions
- [ ] Hide .env file from web access
- [ ] Enable firewall rules if needed

## Monitoring

### Log Files Location:
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Server logs (may vary by hosting provider)
tail -f ~/logs/access.log
tail -f ~/logs/error.log
```

### Health Check Script:
```bash
# Create a simple health check
echo "<?php
echo 'Status: ' . (app()->isDownForMaintenance() ? 'DOWN' : 'UP') . PHP_EOL;
echo 'Environment: ' . app()->environment() . PHP_EOL;
echo 'Database: ' . (DB::connection()->getPdo() ? 'Connected' : 'Failed') . PHP_EOL;
" > health-check.php
```

## Backup Strategy

```bash
# Create automated backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf backup_$DATE.tar.gz --exclude='node_modules' --exclude='vendor' .
# Upload to external storage or keep local
```

---

**Next Steps:**
1. Create GitHub repository
2. Push your code
3. SSH into Hostinger
4. Follow server setup steps
5. Test your application

Would you like me to help you with any specific step?
