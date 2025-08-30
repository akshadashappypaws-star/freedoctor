# Complete VPS Laravel Deployment Guide - README

## 📁 Guide Structure

This deployment guide contains 5 comprehensive documents covering every aspect of Laravel VPS deployment:

### 📄 1. [GitHub Clone Deployment](./1_GITHUB_CLONE_DEPLOYMENT.md)
**Complete step-by-step deployment using GitHub repository**
- Server setup (Apache, MySQL, PHP, Composer)
- Database configuration  
- GitHub repository cloning
- Laravel configuration and optimization
- SSL certificate setup
- Troubleshooting guide

**Best for:** New deployments, team collaboration, version control

---

### 📄 2. [Local to VPS Deployment](./2_LOCAL_TO_VPS_DEPLOYMENT.md)
**Deploy directly from your local machine to VPS**
- SCP/RSYNC upload methods
- FTP/SFTP deployment
- Archive upload and extraction
- Automated deployment scripts
- Permission and configuration setup

**Best for:** Quick deployments, no Git setup, direct file transfer

---

### 📄 3. [Quick Update Methods](./3_QUICK_UPDATE_METHODS.md)  
**Fastest ways to update your deployed application**
- Git pull updates (one-line commands)
- RSYNC incremental updates
- Modified files only uploads
- Database-only updates
- Emergency rollback procedures
- Automated update scripts

**Best for:** Regular updates, maintenance, quick fixes

---

### 📄 4. [Browser Access Guide](./4_BROWSER_ACCESS_GUIDE.md)
**How to access and verify your deployed application**
- Direct IP access methods
- Domain access (if configured)
- Temporary development server
- Troubleshooting access issues
- Performance testing
- Mobile compatibility testing

**Best for:** Testing deployment, troubleshooting access issues

---

### 📄 5. [Domain Connection Guide](./5_DOMAIN_CONNECTION_GUIDE.md)
**Complete domain setup and SSL configuration**
- Domain registrar configuration
- DNS records setup (A, CNAME, MX)
- Apache virtual host configuration
- SSL certificate installation (Let's Encrypt, Cloudflare)
- Domain-specific Laravel settings
- Performance optimization

**Best for:** Production deployments, professional setup

---

## 🚀 Quick Start Options

### Option A: GitHub Deployment (Recommended)
1. Push your code to GitHub
2. Follow guide: `1_GITHUB_CLONE_DEPLOYMENT.md`
3. Access via IP: `4_BROWSER_ACCESS_GUIDE.md`
4. Connect domain: `5_DOMAIN_CONNECTION_GUIDE.md`

### Option B: Direct Upload
1. Follow guide: `2_LOCAL_TO_VPS_DEPLOYMENT.md`
2. Access via IP: `4_BROWSER_ACCESS_GUIDE.md`

### Option C: Update Existing Deployment
1. Follow guide: `3_QUICK_UPDATE_METHODS.md`

## 🛠️ Prerequisites

### VPS Requirements
- Ubuntu 20.04+ or Debian 10+
- 1GB+ RAM (2GB+ recommended)
- 10GB+ storage
- Root or sudo access

### Local Requirements
- SSH client
- Git (for GitHub method)
- Text editor
- Terminal/Command prompt

### Laravel Project Requirements
- Laravel 8.0+
- Composer dependencies
- Working .env configuration
- Database migrations ready

## ⚡ One-Line Deployment Commands

### GitHub Method
```bash
# Complete deployment in one command
curl -s https://raw.githubusercontent.com/your-repo/scripts/deploy.sh | bash
```

### Quick Update
```bash
# Update deployed app in one command
ssh root@YOUR_VPS_IP "cd /var/www/html/freedoctor && git pull && composer install --no-dev && php artisan migrate --force && php artisan optimize"
```

### Health Check
```bash
# Verify deployment status
curl -I http://YOUR_VPS_IP && echo "✅ Site is running!"
```

## 🔧 Essential Commands Reference

### Server Management
```bash
# Check Apache status
sudo systemctl status apache2

# Restart Apache  
sudo systemctl restart apache2

# Check disk space
df -h

# Check memory usage
free -h

# View error logs
sudo tail -f /var/log/apache2/error.log
```

### Laravel Management
```bash
# Navigate to project
cd /var/www/html/freedoctor

# Clear all caches
php artisan optimize:clear

# Cache for production
php artisan optimize

# Run migrations
php artisan migrate --force

# Check app status
php artisan --version
```

### Database Management
```bash
# Connect to MySQL
mysql -u freedoctoruser -p freedoctor

# Backup database
mysqldump -u freedoctoruser -p freedoctor > backup.sql

# Restore database
mysql -u freedoctoruser -p freedoctor < backup.sql
```

## 🏁 Deployment Checklist

### Pre-Deployment
- [ ] Code tested locally
- [ ] Database migrations ready
- [ ] .env.production configured
- [ ] Dependencies updated
- [ ] VPS access confirmed

### During Deployment
- [ ] Server packages installed
- [ ] Database created and configured
- [ ] Files uploaded/cloned
- [ ] Permissions set correctly
- [ ] Laravel configured and optimized

### Post-Deployment
- [ ] Site accessible via browser
- [ ] All features working
- [ ] SSL certificate installed (production)
- [ ] Domain connected (if applicable)
- [ ] Backups configured

## 🆘 Common Issues & Solutions

### 500 Internal Server Error
```bash
# Check Laravel logs
tail -n 50 /var/www/html/freedoctor/storage/logs/laravel.log

# Fix storage permissions
sudo chmod -R 775 storage/ bootstrap/cache/
```

### Database Connection Failed  
```bash
# Test MySQL connection
mysql -u freedoctoruser -p freedoctor

# Check .env settings
grep DB_ .env
```

### Site Not Accessible
```bash
# Check Apache is running
sudo systemctl status apache2

# Check firewall
sudo ufw allow 80
sudo ufw allow 443
```

## 📞 Support Resources

### Official Documentation
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Apache Virtual Hosts](https://httpd.apache.org/docs/2.4/vhosts/)
- [Let's Encrypt SSL](https://letsencrypt.org/getting-started/)

### Useful Tools
- **DNS Checker:** [whatsmydns.net](https://whatsmydns.net)
- **SSL Checker:** [ssllabs.com/ssltest](https://ssllabs.com/ssltest)
- **Site Speed Test:** [gtmetrix.com](https://gtmetrix.com)
- **Uptime Monitor:** [uptimerobot.com](https://uptimerobot.com)

## 🎯 Choose Your Path

**New to deployment?** → Start with `1_GITHUB_CLONE_DEPLOYMENT.md`

**Need quick upload?** → Use `2_LOCAL_TO_VPS_DEPLOYMENT.md`

**Updating existing site?** → Follow `3_QUICK_UPDATE_METHODS.md`

**Site not accessible?** → Check `4_BROWSER_ACCESS_GUIDE.md`

**Want custom domain?** → Configure with `5_DOMAIN_CONNECTION_GUIDE.md`

---

**Happy Deploying! 🚀**

*Remember: Always backup your data before making changes to production servers.*
