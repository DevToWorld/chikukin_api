# Domain-Based Folder Separation Guide

## Overview
This guide sets up separate folders for each domain in `/home/user/www/` to handle multiple domains with different configurations.

## Domain Configuration
- **Frontend Domain**: `http://linkinc.sakura.ne.jp/` → `/home/user/www/linkinc/`
- **API Domain**: `https://www.yourdomain.com/` → `/home/user/www/` (Laravel Backend Only)

## Directory Structure

```
/home/user/www/
├── linkinc/                    # Frontend domain (linkinc.sakura.ne.jp)
│   ├── index.html             # Vue.js SPA entry point
│   ├── css/
│   ├── js/
│   ├── img/
│   ├── .htaccess              # Frontend routing
│   └── api/                   # API proxy to www domain
│       └── .htaccess          # API proxy configuration
└── www/                       # API domain (www.yourdomain.com)
    ├── index.php              # Laravel entry point
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    └── .htaccess              # API routing
```

## Deployment Steps

### 1. Create Directory Structure
```bash
# Create main directories
mkdir -p /home/user/www/linkinc
mkdir -p /home/user/www/www

# Create subdirectories for frontend
mkdir -p /home/user/www/linkinc/css
mkdir -p /home/user/www/linkinc/js
mkdir -p /home/user/www/linkinc/img
mkdir -p /home/user/www/linkinc/api
```

### 2. Deploy Frontend (linkinc.sakura.ne.jp)
```bash
# Build Vue.js frontend
npm run build

# Copy build files to linkinc directory
cp -r dist/* /home/user/www/linkinc/
cp deploy/linkinc.htaccess /home/user/www/linkinc/.htaccess
```

### 3. Deploy Backend (www.yourdomain.com)
```bash
# Copy Laravel backend to www directory
cp -r laravel-backend/* /home/user/www/www/

# Set up Laravel environment
cd /home/user/www/www
cp .env.example .env
# Edit .env with production settings
php artisan key:generate
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Set Permissions
```bash
# Set proper permissions for Laravel
chmod -R 755 /home/user/www/www/storage
chmod -R 755 /home/user/www/www/bootstrap/cache
chown -R www-data:www-data /home/user/www/www/storage
chown -R www-data:www-data /home/user/www/www/bootstrap/cache
```

## Domain Configuration

### Frontend Domain (.htaccess for linkinc)
- Handles Vue.js SPA routing
- Proxies API requests to www domain
- Serves static assets

### API Domain (.htaccess for www)
- Handles Laravel API routing
- Serves API endpoints
- Handles CORS for frontend domain

## Testing

### Frontend Testing
- `http://linkinc.sakura.ne.jp/` → Should load Vue.js application
- `http://linkinc.sakura.ne.jp/about` → Should load Vue.js route
- `http://linkinc.sakura.ne.jp/api/test` → Should proxy to API domain

### API Testing
- `https://www.yourdomain.com/api/test` → Should return API response
- `https://www.yourdomain.com/api/health` → Should return health check

## Security Considerations

1. **CORS Configuration**: Ensure API domain allows requests from frontend domain
2. **SSL Certificates**: Set up SSL for both domains
3. **File Permissions**: Set proper permissions for both directories
4. **Environment Variables**: Keep sensitive data in .env files

## Maintenance

### Updates
1. **Frontend Updates**: Rebuild and copy to `/home/user/www/linkinc/`
2. **Backend Updates**: Update Laravel in `/home/user/www/www/`
3. **Database Updates**: Run migrations on API domain

### Backups
1. **Frontend Backup**: Backup `/home/user/www/linkinc/`
2. **Backend Backup**: Backup `/home/user/www/www/`
3. **Database Backup**: Backup database from API domain

## Troubleshooting

### Common Issues
1. **CORS Errors**: Check CORS configuration in Laravel
2. **API Proxy Issues**: Verify .htaccess proxy configuration
3. **File Permissions**: Check permissions on both directories
4. **SSL Issues**: Verify SSL certificates for both domains

### Debug Mode
Enable debug mode temporarily in Laravel `.env`:
```env
APP_DEBUG=true
```

## Performance Optimization

1. **Enable Caching**: Use Laravel caching features
2. **Gzip Compression**: Enable in .htaccess files
3. **CDN**: Consider using CDN for static assets
4. **Database Optimization**: Optimize database queries
