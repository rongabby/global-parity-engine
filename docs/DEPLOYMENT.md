# Deployment Guide

Complete deployment instructions for the Global Parity Engine in various environments.

## Table of Contents

- [Pre-Deployment Checklist](#pre-deployment-checklist)
- [Environment Setup](#environment-setup)
- [Production Configuration](#production-configuration)
- [Deployment Methods](#deployment-methods)
- [Performance Optimization](#performance-optimization)
- [Security Hardening](#security-hardening)
- [Monitoring & Maintenance](#monitoring--maintenance)
- [Troubleshooting](#troubleshooting)

## Pre-Deployment Checklist

Before deploying to production, ensure:

- [ ] WordPress 5.8+ installed and configured
- [ ] PHP 7.4+ with required extensions (mysqli, json, mbstring, curl)
- [ ] MySQL 5.7+ or MariaDB 10.2+ database
- [ ] SSL certificate installed (HTTPS)
- [ ] Google Maps API key obtained and configured
- [ ] All dependencies installed (`composer install --no-dev`)
- [ ] Environment variables configured
- [ ] Database backups configured
- [ ] Server firewall rules configured
- [ ] Monitoring tools setup
- [ ] Code tested in staging environment
- [ ] Performance testing completed
- [ ] Security scan completed

## Environment Setup

### Server Requirements

**Minimum Requirements:**
- CPU: 2 cores
- RAM: 2GB
- Storage: 10GB SSD
- Bandwidth: 100GB/month

**Recommended for Production:**
- CPU: 4+ cores
- RAM: 4GB+
- Storage: 50GB+ SSD
- Bandwidth: Unlimited

### PHP Configuration

Edit `php.ini` with recommended settings:

```ini
# Memory limits
memory_limit = 256M
upload_max_filesize = 64M
post_max_size = 64M

# Execution time
max_execution_time = 300
max_input_time = 300

# Extensions (ensure these are enabled)
extension=mysqli
extension=json
extension=mbstring
extension=curl
extension=gd

# Performance
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### Database Configuration

```sql
-- Create database
CREATE DATABASE wordpress_production 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'wp_user'@'localhost' 
  IDENTIFIED BY 'strong_password_here';

-- Grant privileges
GRANT ALL PRIVILEGES ON wordpress_production.* 
  TO 'wp_user'@'localhost';

FLUSH PRIVILEGES;
```

### Web Server Configuration

#### Apache (.htaccess)

```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress

# Security Headers
<IfModule mod_headers.c>
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
Header set Permissions-Policy "geolocation=(self), microphone=()"
</IfModule>

# Enable Gzip Compression
<IfModule mod_deflate.c>
AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>

# Browser Caching
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType image/jpg "access plus 1 year"
ExpiresByType image/jpeg "access plus 1 year"
ExpiresByType image/gif "access plus 1 year"
ExpiresByType image/png "access plus 1 year"
ExpiresByType text/css "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name yoursite.com www.yoursite.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yoursite.com www.yoursite.com;

    root /var/www/html;
    index index.php;

    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Increase upload size
    client_max_body_size 64M;

    # WordPress permalinks
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # PHP processing
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_intercept_errors on;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.ht {
        deny all;
    }
    
    location ~ /\.git {
        deny all;
    }

    # REST API authentication support
    location ~ ^/wp-json/ {
        try_files $uri $uri/ /index.php?$args;
    }
}
```

## Production Configuration

### wp-config.php

```php
<?php
/**
 * Production WordPress Configuration
 */

// Database Configuration
define('DB_NAME', getenv('WORDPRESS_DB_NAME'));
define('DB_USER', getenv('WORDPRESS_DB_USER'));
define('DB_PASSWORD', getenv('WORDPRESS_DB_PASSWORD'));
define('DB_HOST', getenv('WORDPRESS_DB_HOST'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

// Authentication Keys and Salts
define('AUTH_KEY',         getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY',  getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY',    getenv('LOGGED_IN_KEY'));
define('NONCE_KEY',        getenv('NONCE_KEY'));
define('AUTH_SALT',        getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT',   getenv('LOGGED_IN_SALT'));
define('NONCE_SALT',       getenv('NONCE_SALT'));

// WordPress Database Table prefix
$table_prefix = 'wp_';

// Production Settings
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);

// Performance
define('WP_CACHE', true);
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// Security
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);
define('FORCE_SSL_ADMIN', true);
define('WP_POST_REVISIONS', 5);
define('AUTOSAVE_INTERVAL', 300);

// Performance Optimization
define('EMPTY_TRASH_DAYS', 7);
define('WP_CRON_LOCK_TIMEOUT', 60);

// Google Maps API
define('GOOGLE_MAPS_API_KEY', getenv('GOOGLE_MAPS_API_KEY'));

// Plugin Configuration
define('GLOBAL_PARITY_MAX_RESULTS', 100);
define('GLOBAL_PARITY_CACHE_TTL', 3600);

// Environment Type
define('WP_ENVIRONMENT_TYPE', 'production');

/* That's all, stop editing! */
require_once ABSPATH . 'wp-settings.php';
```

### Environment Variables (.env)

```bash
# Database
WORDPRESS_DB_NAME=wordpress_production
WORDPRESS_DB_USER=wp_user
WORDPRESS_DB_PASSWORD=strong_random_password
WORDPRESS_DB_HOST=localhost

# WordPress Keys (Generate from: https://api.wordpress.org/secret-key/1.1/salt/)
AUTH_KEY='generate-unique-key-here'
SECURE_AUTH_KEY='generate-unique-key-here'
LOGGED_IN_KEY='generate-unique-key-here'
NONCE_KEY='generate-unique-key-here'
AUTH_SALT='generate-unique-salt-here'
SECURE_AUTH_SALT='generate-unique-salt-here'
LOGGED_IN_SALT='generate-unique-salt-here'
NONCE_SALT='generate-unique-salt-here'

# Google Maps
GOOGLE_MAPS_API_KEY=your-production-api-key

# Plugin Settings
GLOBAL_PARITY_MAX_RESULTS=100
GLOBAL_PARITY_CACHE_TTL=3600
```

## Deployment Methods

### Method 1: Manual Deployment (FTP/SFTP)

1. **Build for Production:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

2. **Upload Files:**
   ```bash
   # Via SFTP
   sftp user@yourserver.com
   put -r plugins/global-parity-data /var/www/html/wp-content/plugins/
   put -r plugins/google-earth-integration /var/www/html/wp-content/plugins/
   ```

3. **Set Permissions:**
   ```bash
   ssh user@yourserver.com
   cd /var/www/html/wp-content/plugins
   chmod -R 755 global-parity-data google-earth-integration
   chown -R www-data:www-data global-parity-data google-earth-integration
   ```

4. **Activate Plugins:**
   - Log into WordPress admin
   - Navigate to Plugins → Installed Plugins
   - Activate both plugins

### Method 2: Git Deployment

1. **Clone Repository on Server:**
   ```bash
   cd /var/www/html/wp-content/plugins
   git clone https://github.com/rongabby/global-parity-engine.git
   cd global-parity-engine
   ```

2. **Install Dependencies:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Symlink Plugins:**
   ```bash
   ln -s /var/www/html/wp-content/plugins/global-parity-engine/plugins/global-parity-data /var/www/html/wp-content/plugins/global-parity-data
   ln -s /var/www/html/wp-content/plugins/global-parity-engine/plugins/google-earth-integration /var/www/html/wp-content/plugins/google-earth-integration
   ```

### Method 3: Automated CI/CD (GitHub Actions)

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    tags:
      - 'v*'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      
      - name: Install Dependencies
        run: composer install --no-dev --optimize-autoloader
      
      - name: Deploy via SFTP
        uses: SamKirkland/FTP-Deploy-Action@4.3.0
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          local-dir: ./plugins/
          server-dir: /wp-content/plugins/
```

## Performance Optimization

### Object Caching (Redis)

1. **Install Redis:**
   ```bash
   sudo apt-get install redis-server
   sudo systemctl enable redis-server
   sudo systemctl start redis-server
   ```

2. **Install Redis Object Cache Plugin:**
   ```bash
   cd /var/www/html/wp-content/plugins
   wp plugin install redis-cache --activate
   wp redis enable
   ```

3. **Configure wp-config.php:**
   ```php
   define('WP_REDIS_HOST', '127.0.0.1');
   define('WP_REDIS_PORT', 6379);
   define('WP_REDIS_TIMEOUT', 1);
   define('WP_REDIS_DATABASE', 0);
   ```

### CDN Configuration

For static assets, configure a CDN:

```php
// In wp-config.php
define('WP_CONTENT_URL', 'https://cdn.yoursite.com/wp-content');
```

### Database Optimization

```sql
-- Optimize database tables
OPTIMIZE TABLE wp_posts, wp_postmeta, wp_options;

-- Add indexes for better performance
ALTER TABLE wp_postmeta 
  ADD INDEX meta_key_value (meta_key(20), meta_value(20));
```

### PHP OPcache

Enable and configure OPcache in `php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

## Security Hardening

### File Permissions

```bash
# Directories
find /var/www/html -type d -exec chmod 755 {} \;

# Files
find /var/www/html -type f -exec chmod 644 {} \;

# wp-config.php
chmod 600 /var/www/html/wp-config.php

# Uploads directory (writable)
chmod -R 755 /var/www/html/wp-content/uploads
```

### Firewall Rules (UFW)

```bash
# Enable firewall
sudo ufw enable

# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP/HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Check status
sudo ufw status
```

### Security Plugins

Install and configure:
- **Wordfence Security**: Firewall and malware scanning
- **iThemes Security**: Additional hardening
- **Two-Factor**: 2FA for admin accounts

### Database Security

```sql
-- Remove default admin user (ID 1)
-- Create new admin with different username

-- Restrict database user privileges
REVOKE ALL PRIVILEGES ON *.* FROM 'wp_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX 
  ON wordpress_production.* TO 'wp_user'@'localhost';
```

### SSL/TLS Certificate

```bash
# Using Let's Encrypt
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d yoursite.com -d www.yoursite.com
```

## Monitoring & Maintenance

### Health Checks

Create health check endpoint:

```php
// In plugins/global-parity-data/health-check.php
<?php
header('Content-Type: application/json');

$health = array(
    'status' => 'ok',
    'timestamp' => current_time('mysql'),
    'database' => $wpdb->check_connection() ? 'connected' : 'error',
    'cache' => wp_using_ext_object_cache() ? 'active' : 'inactive'
);

echo json_encode($health);
```

### Monitoring Tools

**Application Performance Monitoring:**
- New Relic
- Datadog
- Scout APM

**Uptime Monitoring:**
- Pingdom
- UptimeRobot
- StatusCake

**Log Management:**
- Papertrail
- Loggly
- CloudWatch Logs

### Backup Strategy

**Daily Backups:**
```bash
#!/bin/bash
# backup.sh

# Variables
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backups"
WP_DIR="/var/www/html"

# Database backup
mysqldump -u wp_user -p'password' wordpress_production > \
  $BACKUP_DIR/db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz $WP_DIR

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

**Add to crontab:**
```bash
0 2 * * * /path/to/backup.sh
```

### Updates & Maintenance

```bash
# Update WordPress core
wp core update

# Update plugins
wp plugin update --all

# Update themes
wp theme update --all

# Clear cache
wp cache flush

# Optimize database
wp db optimize
```

## Troubleshooting

### Common Issues

**White Screen of Death:**
```bash
# Enable debug mode temporarily
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

# Check error log
tail -f /var/www/html/wp-content/debug.log
```

**Memory Limit Errors:**
```php
// Increase memory limit
define('WP_MEMORY_LIMIT', '512M');
```

**Database Connection Errors:**
```bash
# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u wp_user -p wordpress_production
```

**Plugin Conflicts:**
```bash
# Deactivate all plugins via WP-CLI
wp plugin deactivate --all

# Reactivate one by one
wp plugin activate global-parity-data
```

### Performance Issues

**Slow Queries:**
```sql
-- Enable slow query log
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Check slow queries
SELECT * FROM mysql.slow_log;
```

**High Memory Usage:**
```bash
# Check PHP processes
ps aux | grep php-fpm

# Check memory usage
free -h
```

### Security Issues

**Malware Scan:**
```bash
# Using ClamAV
sudo apt-get install clamav
sudo freshclam
sudo clamscan -r /var/www/html
```

**Check File Integrity:**
```bash
# Compare with clean WordPress
wp core verify-checksums
```

## Rollback Plan

If deployment fails:

1. **Database Rollback:**
   ```bash
   mysql -u wp_user -p wordpress_production < backup.sql
   ```

2. **Files Rollback:**
   ```bash
   tar -xzf files_backup.tar.gz -C /var/www/html
   ```

3. **Plugin Deactivation:**
   ```bash
   wp plugin deactivate global-parity-data google-earth-integration
   ```

## Post-Deployment Checklist

- [ ] Verify website is accessible
- [ ] Test login functionality
- [ ] Check REST API endpoints
- [ ] Test map visualization
- [ ] Verify data entry functionality
- [ ] Check performance metrics
- [ ] Review error logs
- [ ] Test backup restoration
- [ ] Verify SSL certificate
- [ ] Test mobile responsiveness
- [ ] Check analytics tracking
- [ ] Document any issues

---

**Deployment Version**: 1.0  
**Last Updated**: January 2026  
**Maintained by**: Global Parity Engine Team
