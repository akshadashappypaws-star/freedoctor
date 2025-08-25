@echo off
REM Simple Docker deployment without Docker Compose

echo ========================================
echo   FreeDoctorWeb Simple Docker Deploy
echo ========================================

REM Check if Docker is installed
docker --version >nul 2>&1
if errorlevel 1 (
    echo ❌ Docker is not installed. Please install Docker Desktop first.
    echo 📥 Download from: https://www.docker.com/products/docker-desktop
    pause
    exit /b 1
)

echo ✅ Docker is available

REM Build the Docker image
echo 🏗️  Building Docker image...
docker build -f Dockerfile.simple -t freedoctor-web .

if errorlevel 1 (
    echo ❌ Docker build failed
    pause
    exit /b 1
)

REM Stop any existing container
echo 🛑 Stopping existing container...
docker stop freedoctor-web-container 2>nul
docker rm freedoctor-web-container 2>nul

REM Run MySQL database container
echo 🗄️  Starting MySQL database...
docker run -d ^
  --name freedoctor-mysql ^
  -e MYSQL_ROOT_PASSWORD=root123 ^
  -e MYSQL_DATABASE=freedoctor ^
  -e MYSQL_USER=freedoctor ^
  -e MYSQL_PASSWORD=freedoctor123 ^
  -p 3307:3306 ^
  --restart unless-stopped ^
  mysql:8.0

REM Wait for MySQL to be ready
echo ⏳ Waiting for MySQL to start...
timeout /t 30 /nobreak >nul

REM Run the application container
echo 🚀 Starting FreeDoctorWeb application...
docker run -d ^
  --name freedoctor-web-container ^
  --link freedoctor-mysql:db ^
  -p 8080:80 ^
  -e DB_HOST=db ^
  -e DB_DATABASE=freedoctor ^
  -e DB_USERNAME=freedoctor ^
  -e DB_PASSWORD=freedoctor123 ^
  --restart unless-stopped ^
  freedoctor-web

if errorlevel 1 (
    echo ❌ Failed to start application container
    pause
    exit /b 1
)

echo ⏳ Waiting for application to start...
timeout /t 20 /nobreak >nul

REM Run database migrations
echo 📊 Running database migrations...
docker exec freedoctor-web-container php artisan migrate --force

echo.
echo ========================================
echo ✅ Deployment Complete!
echo ========================================
echo.
echo 🌐 Application: http://localhost:8080
echo 📊 MySQL Database: localhost:3307
echo.
echo Database Credentials:
echo   Database: freedoctor
echo   Username: freedoctor
echo   Password: freedoctor123
echo.
echo Useful commands:
echo   View app logs: docker logs -f freedoctor-web-container
echo   View db logs: docker logs -f freedoctor-mysql
echo   Stop containers: docker stop freedoctor-web-container freedoctor-mysql
echo   Remove containers: docker rm freedoctor-web-container freedoctor-mysql
echo   Shell access: docker exec -it freedoctor-web-container bash
echo.

pause
