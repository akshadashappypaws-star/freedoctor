#!/bin/bash

# FreeDoctorWeb Docker Deployment Script

echo "========================================"
echo "  FreeDoctorWeb Docker Deployment"
echo "========================================"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

echo "✅ Docker and Docker Compose are available"

# Copy environment file
echo "📋 Setting up environment configuration..."
if [ ! -f .env ]; then
    cp .env.docker .env
    echo "✅ Environment file created from .env.docker"
else
    echo "⚠️  .env file already exists, skipping copy"
fi

# Generate application key if needed
echo "🔑 Generating application key..."
docker-compose run --rm app php artisan key:generate

# Stop any existing containers
echo "🛑 Stopping existing containers..."
docker-compose down

# Build and start containers
echo "🏗️  Building Docker images..."
docker-compose build --no-cache

echo "🚀 Starting containers..."
docker-compose up -d

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 30

# Run database migrations
echo "📊 Running database migrations..."
docker-compose exec app php artisan migrate --force

# Seed database (optional)
echo "🌱 Seeding database..."
docker-compose exec app php artisan db:seed --force

# Clear and cache configuration
echo "🧹 Clearing and caching configuration..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Set proper permissions
echo "🔧 Setting permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo ""
echo "========================================"
echo "✅ Deployment Complete!"
echo "========================================"
echo ""
echo "🌐 Application: http://localhost:8080"
echo "🗄️  Database (phpMyAdmin): http://localhost:8081"
echo "📊 MySQL Database: localhost:3307"
echo ""
echo "Database Credentials:"
echo "  Database: freedoctor"
echo "  Username: freedoctor"
echo "  Password: freedoctor123"
echo ""
echo "Useful commands:"
echo "  View logs: docker-compose logs -f"
echo "  Stop containers: docker-compose down"
echo "  Restart: docker-compose restart"
echo "  Shell access: docker-compose exec app bash"
echo ""
