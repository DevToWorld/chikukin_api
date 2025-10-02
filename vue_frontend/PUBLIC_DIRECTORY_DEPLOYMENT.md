# Laravel Public Directory Deployment Guide

This guide explains how to deploy Laravel by redirecting the document root to the `/public` directory.

## Overview

When deploying Laravel to a shared hosting environment like Sakura, you often need to redirect all requests from the document root to Laravel's `public` directory. This is because Laravel's entry point (`index.php`) is located in the `public` folder.

## Directory Structure

```
/www/ (document root)
├── .htaccess (this file)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── index.php (Laravel entry point)
│   ├── .htaccess (Laravel's default .htaccess)
│   ├── css/
│   ├── js/
│   └── img/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
├── composer.json
└── composer.lock
```

## Deployment Steps

### 1. Upload Laravel Files
Upload your entire Laravel project to the document root (`/www/` or `/public_html/`).

### 2. Upload .htaccess File
Upload the `document-root-public.htaccess` file as `.htaccess` to the document root.

### 3. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env
chmod 644 .htaccess
```

### 4. Configure Environment
Create or update your `.env` file:
```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## How It Works

The `.htaccess` file uses Apache's `mod_rewrite` to redirect all requests:

```apache
RewriteRule ^(.*)$ public/$1 [L,QSA]
```

This means:
- `https://yourdomain.com/` → `https://yourdomain.com/public/`
- `https://yourdomain.com/api/test` → `https://yourdomain.com/public/api/test`
- `https://yourdomain.com/css/app.css` → `https://yourdomain.com/public/css/app.css`

## Testing

After deployment, test these endpoints:

1. **Root URL**: `https://yourdomain.com/`
   - Should return Laravel's welcome message or your API response

2. **API Endpoints**: `https://yourdomain.com/api/test`
   - Should return your API response

3. **Health Check**: `https://yourdomain.com/api/health`
   - Should return health status

## Security Features

The `.htaccess` file includes several security measures:

1. **File Protection**: Blocks access to sensitive files like `.env`, `composer.json`, etc.
2. **Directory Protection**: Prevents access to `storage/` and `bootstrap/` directories
3. **Security Headers**: Adds security headers like X-Frame-Options, X-Content-Type-Options
4. **CORS Headers**: Configures CORS for API access

## Performance Features

1. **Gzip Compression**: Compresses text files for faster loading
2. **Browser Caching**: Sets appropriate cache headers for static assets
3. **Directory Browsing**: Disabled for security

## Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check `.htaccess` syntax
   - Verify file permissions
   - Check Laravel logs in `storage/logs/laravel.log`

2. **404 Not Found**
   - Ensure `public/index.php` exists
   - Check if `public/.htaccess` exists
   - Verify Laravel routes

3. **Permission Denied**
   - Check file permissions: `chmod -R 755 storage bootstrap/cache`
   - Verify web server can read files

4. **Database Connection Error**
   - Verify database credentials in `.env`
   - Check if database exists and is accessible

### Debug Mode
To enable debug mode temporarily:
```env
APP_DEBUG=true
```

## Alternative Approaches

### Option 1: Direct Public Directory Access
If your hosting provider allows it, you can set the document root directly to the `public` directory. This eliminates the need for URL rewriting.

### Option 2: Subdomain Approach
Deploy Laravel to a subdomain (e.g., `api.yourdomain.com`) and point the document root directly to the `public` directory.

### Option 3: Symbolic Link
Create a symbolic link from the document root to the `public` directory:
```bash
ln -s public index
```

## Maintenance

### Regular Updates
1. Update Laravel dependencies: `composer update`
2. Clear caches: `php artisan cache:clear`
3. Re-cache configurations: `php artisan config:cache`

### Backup Strategy
1. Database backups (via hosting control panel)
2. File backups (FTP/SFTP)
3. Configuration backups (`.env` file)

## Support

If you encounter issues:
1. Check hosting server logs
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify file permissions and ownership
4. Test individual components separately

---

**Note**: This approach is commonly used for shared hosting environments where you cannot change the document root directly.
