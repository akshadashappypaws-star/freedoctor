#!/bin/bash

# FreeDoctor Web Application Deployment Script for Hostinger
# This script should be run on your Hostinger server

echo "🚀 Starting FreeDoctor deployment..."

# Navigate to your domain's public_html directory
cd /home/u150415685/domains/your-domain.com/public_html

# Backup current deployment (optional)
if [ -d "backup" ]; then
    rm -rf backup
fi

if [ -d "app" ]; then
    echo "📦 Creating backup of current deployment..."
    mkdir backup
    cp -r * backup/ 2>/dev/null || true
fi

# Pull latest changes from GitHub
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# Install/Update Composer dependencies
echo "📚 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install/Update NPM dependencies and build assets
echo "🎨 Building frontend assets..."
npm install --production
npm run build

# Run Laravel optimizations
echo "⚡ Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Run any pending migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear any cached data
echo "🧹 Clearing caches..."
php artisan cache:clear

# Restart services (if needed)
echo "🔄 Restarting services..."
# Add any service restart commands here if needed

echo "✅ Deployment completed successfully!"
echo "🌐 Your application is now live!"

# Display some useful information
echo ""
echo "📊 Deployment Summary:"
echo "- Time: $(date)"
echo "- Git commit: $(git log -1 --pretty=format:'%h - %s (%cr)')"
echo "- PHP version: $(php -v | head -n1)"
echo "- Laravel version: $(php artisan --version)"
