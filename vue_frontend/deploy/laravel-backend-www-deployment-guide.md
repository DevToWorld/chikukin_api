# Laravel Backend Deployment Guide for www Subdomain

## Overview
This guide covers deploying only the Laravel backend API to the `www` subdomain on your server. This setup is ideal when you want to host your API separately from your frontend application.

## Target Configuration
- **API Domain**: `https://www.yourdomain.com/` → `/home/user/www/`
- **Laravel Backend**: Located in `laravel-backend/` directory
- **Web Server**: Apache with mod_rewrite enabled

## Directory Structure

```
/home/user/www/
├── index.php              # Laravel entry point (from public/index.php)
├── app/                   # Laravel application files
├── bootstrap/             # Laravel bootstrap files
├── config/                # Laravel configuration
├── database/              # Database files and migrations
├── public/                # Public assets (CSS, JS, images)
├── routes/                # Route definitions
├── storage/               # Storage directory (logs, cache, etc.)
├── vendor/                # Composer dependencies
├── .env                   # Environment configuration
├── .htaccess              # Apache configuration
└── artisan                # Laravel command line tool
```

## Deployment Steps

### 1. Prepare Laravel Backend

```bash
# Navigate to the Laravel backend directory
cd laravel-backend

# Copy environment file and configure
cp .env.example .env

# Generate application key
php artisan key:generate

# Install dependencies (production)
composer install --optimize-autoloader --no-dev

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Deploy to www Directory

```bash
# Copy entire Laravel backend to www directory
cp -r laravel-backend/* /home/user/www/

# Set proper permissions
chmod -R 755 /home/user/www/storage
chmod -R 755 /home/user/www/bootstrap/cache
chown -R www-data:www-data /home/user/www/storage
chown -R www-data:www-data /home/user/www/bootstrap/cache
```

### 3. Configure Apache (.htaccess)

Copy the provided `.htaccess` file to your www directory:

```bash
# Copy the Laravel backend .htaccess configuration
cp laravel-backend-www.htaccess /home/user/www/.htaccess
```

The `.htaccess` file includes:
- Laravel routing rules
- CORS headers for API access
- Security headers
- Cache optimization
- Gzip compression
- File protection

### 4. Environment Configuration

Update `/home/user/www/.env` with production settings:

```env
APP_NAME="Your API Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.yourdomain.com

# Database configuration
DB_CONNECTION=mysql
DB_HOST=your_database_host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# CORS configuration
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,subdomain.yourdomain.com
SESSION_DOMAIN=.yourdomain.com

# Cache and session
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail configuration (if needed)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
```

### 5. Database Setup

```bash
# Navigate to www directory
cd /home/user/www

# Run database migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Create storage link (if using file storage)
php artisan storage:link
```

### 6. Final Permissions and Ownership

```bash
# Set final permissions
chown -R www-data:www-data /home/user/www
find /home/user/www/storage -type d -exec chmod 775 {} \;
find /home/user/www/storage -type f -exec chmod 664 {} \;
find /home/user/www/bootstrap/cache -type d -exec chmod 775 {} \;
find /home/user/www/bootstrap/cache -type f -exec chmod 664 {} \;
```

## Testing the Deployment

### API Endpoints Test

```bash
# Test basic API endpoint
curl https://www.yourdomain.com/api/seminars

# Test health check (if implemented)
curl https://www.yourdomain.com/api/health

# Test CORS (from browser console)
fetch('https://www.yourdomain.com/api/seminars')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Expected Responses

- **Successful API call**: JSON response with data
- **CORS preflight**: 200 OK with appropriate headers
- **404 errors**: Properly handled by Laravel
- **500 errors**: Check Laravel logs in `/home/user/www/storage/logs/`

## Security Considerations

### 1. File Protection
The `.htaccess` file protects sensitive files:
- `.env` files
- `composer.json` and `composer.lock`
- `package.json`
- `artisan` command
- `storage/` and `bootstrap/` directories

### 2. CORS Configuration
Update CORS settings in Laravel if needed:
- Configure allowed origins in `config/cors.php`
- Set appropriate headers in `.htaccess`

### 3. SSL Certificate
Ensure SSL is properly configured for your domain:
```bash
# Test SSL configuration
curl -I https://www.yourdomain.com
```

## Maintenance and Updates

### 1. Updating Laravel Backend

```bash
# Navigate to project directory
cd /path/to/your/project

# Update Laravel backend
cd laravel-backend
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Deploy to www
cp -r laravel-backend/* /home/user/www/

# Set permissions
chown -R www-data:www-data /home/user/www
```

### 2. Database Migrations

```bash
cd /home/user/www
php artisan migrate --force
```

### 3. Log Monitoring

```bash
# Monitor Laravel logs
tail -f /home/user/www/storage/logs/laravel.log

# Monitor Apache error logs
tail -f /var/log/apache2/error.log
```

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check file permissions: `ls -la /home/user/www/storage`
   - Check Laravel logs: `cat /home/user/www/storage/logs/laravel.log`
   - Verify .htaccess syntax

2. **CORS Errors**
   - Verify CORS headers in `.htaccess`
   - Check Laravel CORS configuration
   - Test with browser developer tools

3. **Database Connection Errors**
   - Verify database credentials in `.env`
   - Check database server status
   - Test connection: `php artisan tinker`

4. **File Permission Errors**
   - Fix storage permissions: `chmod -R 775 /home/user/www/storage`
   - Fix ownership: `chown -R www-data:www-data /home/user/www`

### Debug Mode

For troubleshooting, temporarily enable debug mode:

```env
APP_DEBUG=true
APP_ENV=local
```

**Remember to disable debug mode in production!**

## Performance Optimization

### 1. Enable OPcache (if available)

```ini
; In php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### 2. Laravel Optimization

```bash
cd /home/user/www
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 3. Web Server Optimization

The `.htaccess` file includes:
- Gzip compression
- Browser caching
- Security headers
- Optimized rewrite rules

## Backup Strategy

### 1. Application Backup

```bash
# Create backup of Laravel application
tar -czf laravel-backup-$(date +%Y%m%d).tar.gz /home/user/www

# Exclude vendor directory for smaller backup
tar -czf laravel-backup-no-vendor-$(date +%Y%m%d).tar.gz \
  --exclude='/home/user/www/vendor' \
  --exclude='/home/user/www/storage/logs' \
  /home/user/www
```

### 2. Database Backup

```bash
# MySQL backup
mysqldump -u username -p database_name > backup-$(date +%Y%m%d).sql

# SQLite backup (if using SQLite)
cp /home/user/www/database/database.sqlite backup-$(date +%Y%m%d).sqlite
```

## Quick Deployment Script

Create a deployment script for easy updates:

```bash
#!/bin/bash
# deploy-www.sh

echo "Deploying Laravel backend to www..."

# Navigate to project
cd /path/to/your/project/laravel-backend

# Update dependencies
composer install --optimize-autoloader --no-dev

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Copy to www
cp -r * /home/user/www/

# Set permissions
chown -R www-data:www-data /home/user/www
chmod -R 755 /home/user/www/storage
chmod -R 755 /home/user/www/bootstrap/cache

echo "Deployment complete!"
```

Make it executable:
```bash
chmod +x deploy-www.sh
```

## Conclusion

This setup provides a clean separation where your Laravel API is hosted on the `www` subdomain, making it easy to:
- Scale your API independently
- Use different domains for frontend and backend
- Implement proper CORS policies
- Maintain security best practices

Your API will be accessible at `https://www.yourdomain.com/api/` endpoints, ready to serve your frontend applications or external clients.
