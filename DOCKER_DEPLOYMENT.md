# FreeDoctorWeb Docker Deployment Guide

## Overview
This guide provides multiple ways to deploy FreeDoctorWeb using Docker containers for easy development, testing, and production deployment.

## Prerequisites

### Required Software
- **Docker Desktop**: Download from [https://www.docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)
- **Git** (if cloning from repository)

### System Requirements
- **Windows 10/11** with WSL2 enabled
- **8GB RAM** minimum (16GB recommended)
- **10GB free disk space**

## Deployment Options

### Option 1: Simple Docker Deployment (Recommended for beginners)

1. **Install Docker Desktop**
   - Download and install Docker Desktop
   - Start Docker Desktop and ensure it's running

2. **Deploy the application**
   ```batch
   # Run the simple deployment script
   deploy-simple-docker.bat
   ```

3. **Access the application**
   - Application: http://localhost:8080
   - Database: localhost:3307

### Option 2: Docker Compose Deployment (Full stack)

1. **Install Docker Compose** (usually included with Docker Desktop)

2. **Deploy with Docker Compose**
   ```batch
   # For Windows
   deploy-docker.bat
   
   # For Linux/macOS
   chmod +x deploy-docker.sh
   ./deploy-docker.sh
   ```

3. **Access services**
   - Application: http://localhost:8080
   - phpMyAdmin: http://localhost:8081
   - Nginx (Load Balancer): http://localhost
   - MySQL Database: localhost:3307

## Configuration

### Environment Variables
Copy and modify the environment file:
```batch
copy .env.example .env
```

Key configurations to update:
- `APP_URL=http://localhost:8080`
- `DB_HOST=db` (for Docker)
- `WHATSAPP_ACCESS_TOKEN=your-token`
- `GOOGLE_MAPS_API_KEY=your-api-key`
- `RAZORPAY_KEY_ID=your-key`

### Database Configuration
Default database credentials:
- **Database**: freedoctor
- **Username**: freedoctor  
- **Password**: freedoctor123
- **Root Password**: root123

## Docker Services

### Application Container (freedoctor-web)
- **Base Image**: PHP 8.1 with Apache
- **Port**: 8080
- **Features**: Laravel, WhatsApp API, Google Maps

### Database Container (MySQL 8.0)
- **Port**: 3307
- **Persistent Storage**: Docker volume `mysql_data`

### phpMyAdmin (Optional)
- **Port**: 8081
- **Access**: http://localhost:8081

### Nginx Load Balancer (Optional)
- **Port**: 80, 443
- **SSL**: Ready for certificate mounting

## Management Commands

### View Logs
```bash
# Application logs
docker logs -f freedoctor-web-container

# Database logs  
docker logs -f freedoctor-mysql

# All services (Docker Compose)
docker-compose logs -f
```

### Shell Access
```bash
# Access application container
docker exec -it freedoctor-web-container bash

# Access database
docker exec -it freedoctor-mysql mysql -u freedoctor -p
```

### Start/Stop Services
```bash
# Stop containers
docker stop freedoctor-web-container freedoctor-mysql

# Start containers
docker start freedoctor-web-container freedoctor-mysql

# Docker Compose
docker-compose stop
docker-compose start
```

### Database Management
```bash
# Run migrations
docker exec freedoctor-web-container php artisan migrate

# Seed database
docker exec freedoctor-web-container php artisan db:seed

# Clear cache
docker exec freedoctor-web-container php artisan cache:clear
```

## Troubleshooting

### Common Issues

1. **Port Already in Use**
   ```bash
   # Check what's using port 8080
   netstat -ano | findstr :8080
   
   # Stop conflicting service or change port
   ```

2. **Docker Not Starting**
   - Ensure Docker Desktop is running
   - Check Windows features: Hyper-V, WSL2
   - Restart Docker Desktop

3. **Database Connection Errors**
   - Wait 30-60 seconds for MySQL to initialize
   - Check database container logs
   - Verify environment variables

4. **Permission Errors**
   ```bash
   # Fix permissions
   docker exec freedoctor-web-container chown -R www-data:www-data storage bootstrap/cache
   ```

### Health Checks
```bash
# Check container status
docker ps

# Health check
curl http://localhost:8080/health

# Database connection test
docker exec freedoctor-web-container php artisan migrate:status
```

## Production Deployment

### Security Considerations
- Change default database passwords
- Set `APP_DEBUG=false`
- Configure SSL certificates
- Set up proper backup strategy
- Use environment-specific configurations

### Performance Optimization
- Enable PHP OPcache
- Configure Redis for caching
- Set up CDN for static assets
- Optimize database queries
- Use production-grade web server

### Monitoring
- Set up log aggregation
- Configure health checks
- Monitor resource usage
- Set up alerts

## Backup and Recovery

### Database Backup
```bash
# Create backup
docker exec freedoctor-mysql mysqldump -u freedoctor -pfreedoctor123 freedoctor > backup.sql

# Restore backup
docker exec -i freedoctor-mysql mysql -u freedoctor -pfreedoctor123 freedoctor < backup.sql
```

### Full Application Backup
```bash
# Backup application data
docker run --rm -v freedoctor_mysql_data:/data -v $(pwd):/backup alpine tar czf /backup/mysql_backup.tar.gz /data
```

## Support

### Documentation
- Laravel: https://laravel.com/docs
- Docker: https://docs.docker.com
- WhatsApp Business API: https://developers.facebook.com/docs/whatsapp

### Common Commands Reference
```bash
# Rebuild containers
docker-compose build --no-cache

# Update dependencies
docker exec freedoctor-web-container composer update

# Clear all Docker data (WARNING: Destructive)
docker system prune -a --volumes
```

## Development

### Local Development with Docker
1. Mount source code as volume for live editing
2. Use development environment variables
3. Enable debug mode
4. Set up hot reloading for assets

### Testing
```bash
# Run tests in container
docker exec freedoctor-web-container php artisan test

# Run specific test
docker exec freedoctor-web-container php artisan test --filter=ExampleTest
```
