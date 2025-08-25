#!/bin/bash

# FreeDoctorCORPO - Hostinger Git Deployment Script
echo "🚀 Deploying FreeDoctorCORPO to Hostinger..."

# Step 1: Initialize Git repository on Hostinger (if not already done)
echo "📂 Setting up Git repository..."
cd /home/u150415685/domains/freedoctor.in/public_html

# Initialize bare repository for receiving pushes
if [ ! -d ".git" ]; then
    git init --bare
    echo "✅ Git repository initialized"
else
    echo "✅ Git repository already exists"
fi

# Step 2: Set up post-receive hook
echo "🔧 Setting up deployment hook..."
cat > hooks/post-receive << 'EOF'
#!/bin/bash
cd /home/u150415685/domains/freedoctor.in/public_html
env -i git reset --hard

echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔧 Setting up Laravel..."
# Copy environment file if not exists
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "📝 Created .env file - Please update with your database credentials"
fi

# Generate application key if not set
php artisan key:generate --force

# Run migrations
php artisan migrate --force

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your application is now live at: https://freedoctor.in"
EOF

chmod +x hooks/post-receive
echo "✅ Post-receive hook configured"

# Step 3: Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

echo "🎉 Hostinger server setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. Update .env file with your database credentials"
echo "2. Push your code from local machine: git push hostinger main"
echo "3. Your app will be live at: https://freedoctor.in"
