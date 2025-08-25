@echo off
REM FreeDoctorWeb Docker Deployment Script for Windows

echo ========================================
echo   FreeDoctorWeb Docker Deployment
echo ========================================

REM Check if Docker is installed
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker is not installed. Please install Docker first.
    pause
    exit /b 1
)

docker-compose --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker Compose is not installed. Please install Docker Compose first.
    pause
    exit /b 1
)

echo ✅ Docker and Docker Compose are available

REM Copy environment file
echo 📋 Setting up environment configuration...
if not exist .env (
    copy .env.docker .env
    echo ✅ Environment file created from .env.docker
) else (
    echo ⚠️  .env file already exists, skipping copy
)

REM Stop any existing containers
echo 🛑 Stopping existing containers...
docker-compose down

REM Build and start containers
echo 🏗️  Building Docker images...
docker-compose build --no-cache

echo 🚀 Starting containers...
docker-compose up -d

REM Wait for database to be ready
echo ⏳ Waiting for database to be ready...
timeout /t 30 /nobreak >nul

REM Generate application key
echo 🔑 Generating application key...
docker-compose exec app php artisan key:generate --force

REM Run database migrations
echo 📊 Running database migrations...
docker-compose exec app php artisan migrate --force

REM Seed database (optional)
echo 🌱 Seeding database...
docker-compose exec app php artisan db:seed --force

REM Clear and cache configuration
echo 🧹 Clearing and caching configuration...
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

REM Set proper permissions
echo 🔧 Setting permissions...
docker-compose exec app chown -R www-data:www-data /var/www/html/storage
docker-compose exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo.
echo ========================================
echo ✅ Deployment Complete!
echo ========================================
echo.
echo 🌐 Application: http://localhost:8080
echo 🗄️  Database (phpMyAdmin): http://localhost:8081
echo 📊 MySQL Database: localhost:3307
echo.
echo Database Credentials:
echo   Database: freedoctor
echo   Username: freedoctor
echo   Password: freedoctor123
echo.
echo Useful commands:
echo   View logs: docker-compose logs -f
echo   Stop containers: docker-compose down
echo   Restart: docker-compose restart
echo   Shell access: docker-compose exec app bash
echo.

pause
