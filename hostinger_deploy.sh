#!/bin/bash

# FreeDoctorCORPO - Hostinger Deployment Commands
# Run these commands in Hostinger Terminal (Control Panel > Advanced > Terminal)

echo "🚀 FreeDoctorCORPO Deployment Starting..."

# Navigate to domain directory
cd /home/u150415685/domains/freedoctor.in/public_html

# Backup existing files
if [ "$(ls -A .)" ]; then
    echo "📦 Backing up existing files..."
    mkdir -p backup-$(date +%Y%m%d_%H%M%S)
    mv * backup-$(date +%Y%m%d_%H%M%S)/ 2>/dev/null || true
    mv .* backup-$(date +%Y%m%d_%H%M%S)/ 2>/dev/null || true
fi

# Clone the repository from GitHub
echo "📥 Cloning from GitHub..."
git clone https://github.com/Mytechdata/freedoctor-development.git .

# Check if clone was successful
if [ ! -f "artisan" ]; then
    echo "❌ Clone failed. Please check if git is available."
    exit 1
fi

echo "✅ Repository cloned successfully"

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader
    echo "✅ Composer dependencies installed"
else
    echo "⚠️ Composer not found. You may need to install dependencies manually."
fi

# Setup Laravel environment
echo "🔧 Setting up Laravel..."

# Copy environment file
cp .env.example .env
echo "✅ Environment file created"

# Generate application key
php artisan key:generate --force
echo "✅ Application key generated"

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 644 .env
echo "✅ Permissions set"

# Clear any existing caches
php artisan cache:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo ""
echo "🎉 Deployment completed successfully!"
echo ""
echo "📝 Manual steps required:"
echo "1. Edit .env file with your database credentials:"
echo "   nano .env"
echo ""
echo "2. Update these values in .env:"
echo "   APP_ENV=production"
echo "   APP_DEBUG=false"
echo "   APP_URL=https://freedoctor.in"
echo "   DB_DATABASE=u150415685_freedoctor"
echo "   DB_USERNAME=u150415685_dbuser"
echo "   DB_PASSWORD=your_password"
echo ""
echo "3. Run database migrations:"
echo "   php artisan migrate --force"
echo ""
echo "4. Cache configurations:"
echo "   php artisan config:cache"
echo "   php artisan route:cache"
echo "   php artisan view:cache"
echo ""
echo "5. Test your application:"
echo "   https://freedoctor.in"
echo "   https://freedoctor.in/webhook/whatsapp?hub.mode=subscribe&hub.verify_token=FreeDoctor2025SecureToken&hub.challenge=test123"
echo ""
echo "🌐 Your FreeDoctorCORPO application is ready!"
