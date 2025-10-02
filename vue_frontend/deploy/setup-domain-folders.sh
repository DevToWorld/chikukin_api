#!/bin/bash

# Domain-Based Folder Setup Script
# This script sets up separate folders for each domain in /home/user/www/

set -e

# Configuration
FRONTEND_DOMAIN="linkinc.sakura.ne.jp"
API_DOMAIN="apilinkinc.topaz.ne.jp"
BASE_DIR="/home/user/www"
FRONTEND_DIR="$BASE_DIR/linkinc"
API_DIR="$BASE_DIR/apilinkinc"

echo "🚀 Setting up domain-based folder structure..."
echo "Frontend Domain: $FRONTEND_DOMAIN → $FRONTEND_DIR"
echo "API Domain: $API_DOMAIN → $API_DIR"

# Create main directories
echo "📁 Creating directory structure..."
mkdir -p "$FRONTEND_DIR"
mkdir -p "$API_DIR"

# Create subdirectories for frontend
mkdir -p "$FRONTEND_DIR/css"
mkdir -p "$FRONTEND_DIR/js"
mkdir -p "$FRONTEND_DIR/img"
mkdir -p "$FRONTEND_DIR/api"

echo "✅ Directory structure created successfully!"

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 "$FRONTEND_DIR"
chmod -R 755 "$API_DIR"

# Create .htaccess files
echo "📝 Creating .htaccess files..."

# Frontend .htaccess
cat > "$FRONTEND_DIR/.htaccess" << 'EOF'
RewriteEngine On

# Handle API requests - proxy to apilinkinc domain
RewriteRule ^api/(.*)$ https://apilinkinc.topaz.ne.jp/api/$1 [P,L,QSA]

# Serve Vue.js SPA for all other requests
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.*$ /index.html [L,QSA]

# Security headers
<IfModule mod_headers.c>
    # CORS headers for API proxy
    <LocationMatch "^/api/">
        Header always set Access-Control-Allow-Origin "https://apilinkinc.topaz.ne.jp"
        Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, PATCH, OPTIONS"
        Header always set Access-Control-Allow-Headers "Accept, Authorization, Content-Type, Origin, X-Requested-With, X-CSRF-TOKEN"
        Header always set Access-Control-Allow-Credentials "true"
    </LocationMatch>
    
    # Security headers for frontend
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/html "access plus 0 seconds"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType font/woff "access plus 1 month"
    ExpiresByType font/woff2 "access plus 1 month"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/json
</IfModule>
EOF

# API .htaccess
cat > "$API_DIR/.htaccess" << 'EOF'
RewriteEngine On

# Handle Laravel API requests
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^.*$ /index.php [L,QSA]

# Security headers
<IfModule mod_headers.c>
    # CORS headers for API
    <LocationMatch "^/api/">
        Header always set Access-Control-Allow-Origin "http://linkinc.sakura.ne.jp"
        Header always set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, PATCH, OPTIONS"
        Header always set Access-Control-Allow-Headers "Accept, Authorization, Content-Type, Origin, X-Requested-With, X-CSRF-TOKEN"
        Header always set Access-Control-Allow-Credentials "true"
        Header always set Vary "Origin"
    </LocationMatch>
    
    # Security headers for API
    Header always set X-Frame-Options "DENY"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set X-API-Version "1.0"
</IfModule>

# Cache static assets (if any)
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType font/woff "access plus 1 month"
    ExpiresByType font/woff2 "access plus 1 month"
</IfModule>

# Gzip compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/json
</IfModule>

# Hide sensitive files
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.json">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.lock">
    Order allow,deny
    Deny from all
</Files>

# Prevent access to Laravel directories
RedirectMatch 403 ^/app/
RedirectMatch 403 ^/bootstrap/
RedirectMatch 403 ^/config/
RedirectMatch 403 ^/database/
RedirectMatch 403 ^/routes/
RedirectMatch 403 ^/storage/
RedirectMatch 403 ^/vendor/
EOF

echo "✅ .htaccess files created successfully!"

# Create deployment instructions
cat > "$BASE_DIR/DEPLOYMENT_INSTRUCTIONS.md" << EOF
# Domain-Based Deployment Instructions

## Directory Structure
- Frontend: $FRONTEND_DIR (for $FRONTEND_DOMAIN)
- API: $API_DIR (for $API_DOMAIN)

## Next Steps

### 1. Deploy Frontend
\`\`\`bash
# Build Vue.js frontend
npm run build

# Copy build files to frontend directory
cp -r dist/* $FRONTEND_DIR/
\`\`\`

### 2. Deploy Backend
\`\`\`bash
# Copy Laravel backend to API directory
cp -r laravel-backend/* $API_DIR/

# Set up Laravel environment
cd $API_DIR
cp .env.example .env
# Edit .env with production settings
php artisan key:generate
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
\`\`\`

### 3. Set Permissions
\`\`\`bash
# Set proper permissions for Laravel
chmod -R 755 $API_DIR/storage
chmod -R 755 $API_DIR/bootstrap/cache
chown -R www-data:www-data $API_DIR/storage
chown -R www-data:www-data $API_DIR/bootstrap/cache
\`\`\`

### 4. Test Deployment
- Frontend: http://$FRONTEND_DOMAIN/
- API: https://$API_DOMAIN/api/test

## Configuration Files
- Frontend .htaccess: $FRONTEND_DIR/.htaccess
- API .htaccess: $API_DIR/.htaccess
- API Config: src/config/api.js (updated for domain separation)

## Security Notes
- CORS is configured between domains
- Sensitive files are protected
- SSL certificates needed for both domains
EOF

echo "📋 Deployment instructions created at $BASE_DIR/DEPLOYMENT_INSTRUCTIONS.md"

echo ""
echo "🎉 Domain-based folder setup completed successfully!"
echo ""
echo "📁 Directory structure:"
echo "   Frontend: $FRONTEND_DIR"
echo "   API: $API_DIR"
echo ""
echo "📝 Next steps:"
echo "   1. Deploy your Vue.js frontend to $FRONTEND_DIR"
echo "   2. Deploy your Laravel backend to $API_DIR"
echo "   3. Configure your web server to point domains to these directories"
echo "   4. Set up SSL certificates for both domains"
echo ""
echo "📖 See $BASE_DIR/DEPLOYMENT_INSTRUCTIONS.md for detailed instructions"
