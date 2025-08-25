#!/bin/bash

# FreeDoctorCORPO - Deploy from GitHub to Hostinger
echo "🚀 Deploying FreeDoctorCORPO from GitHub to Hostinger..."

# Navigate to domain directory
cd /home/u150415685/domains/freedoctor.in/public_html

# Backup any existing files
if [ "$(ls -A .)" ]; then
    echo "📦 Backing up existing files..."
    mkdir -p backup-$(date +%Y%m%d)
    mv * backup-$(date +%Y%m%d)/ 2>/dev/null || true
fi

# Clone the repository
echo "📥 Cloning repository from GitHub..."
git clone https://github.com/Mytechdata/freedoctor-development.git .

# Install dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Setup Laravel
echo "🔧 Setting up Laravel application..."

# Copy environment file
cp .env.example .env
echo "📝 Created .env file"

# Generate application key
php artisan key:generate --force

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Deployment completed!"
echo ""
echo "🔧 Manual steps required:"
echo "1. Edit .env file with your Hostinger database credentials:"
echo "   - DB_DATABASE=u150415685_freedoctor"
echo "   - DB_USERNAME=u150415685_dbuser" 
echo "   - DB_PASSWORD=your_password"
echo "   - APP_URL=https://freedoctor.in"
echo ""
echo "2. Run database migrations:"
echo "   php artisan migrate --force"
echo ""
echo "3. Cache configurations:"
echo "   php artisan config:cache"
echo "   php artisan route:cache"
echo "   php artisan view:cache"
echo ""
echo "🌐 Your application will be live at: https://freedoctor.in"
echo "📱 Test webhook: https://freedoctor.in/webhook/whatsapp"
