#!/bin/bash

# FreeDoctor Hostinger Setup Script
# Run this after uploading files to Hostinger

echo "🚀 Setting up FreeDoctor on Hostinger..."

# Copy environment file
echo "📋 Setting up environment..."
cp .env.hostinger .env

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate --force

# Clear any existing caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Install Composer dependencies (if not already installed)
echo "📚 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Cache configurations for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "✅ Setup completed!"
echo ""
echo "📌 Next Steps:"
echo "1. Update APP_URL in .env with your actual domain"
echo "2. Update WhatsApp webhook URLs with your domain"
echo "3. Set document root to: /public_html/public"
echo "4. Test your application!"
echo ""
echo "🌐 Your FreeDoctor application is ready!"
