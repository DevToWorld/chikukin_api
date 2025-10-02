#!/bin/bash

# Laravel www Deployment Diagnostic Script
# This script helps diagnose 403 Forbidden errors

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Configuration - UPDATE THESE PATHS
WWW_DIR="/home/user/www"  # Update this to your actual www directory path

echo "=========================================="
echo "Laravel www Deployment Diagnostic"
echo "=========================================="

# Check if www directory exists
print_status "Checking www directory..."
if [ ! -d "$WWW_DIR" ]; then
    print_error "WWW directory does not exist: $WWW_DIR"
    echo "Please update WWW_DIR in this script with your actual path"
    exit 1
fi
print_success "WWW directory exists: $WWW_DIR"

# Check if index.php exists
print_status "Checking index.php..."
if [ ! -f "$WWW_DIR/index.php" ]; then
    print_error "index.php not found in $WWW_DIR"
    echo "This is likely the cause of your 403 error!"
    echo "Run: cp laravel-backend/public/index.php $WWW_DIR/"
else
    print_success "index.php found"
    
    # Check if it's the Laravel index.php
    if grep -q "LARAVEL_START" "$WWW_DIR/index.php"; then
        print_success "index.php appears to be Laravel's entry point"
    else
        print_warning "index.php exists but doesn't appear to be Laravel's entry point"
    fi
fi

# Check Laravel structure
print_status "Checking Laravel directory structure..."

required_dirs=("app" "bootstrap" "config" "database" "public" "routes" "storage" "vendor")
missing_dirs=()

for dir in "${required_dirs[@]}"; do
    if [ ! -d "$WWW_DIR/$dir" ]; then
        missing_dirs+=("$dir")
    fi
done

if [ ${#missing_dirs[@]} -eq 0 ]; then
    print_success "All required Laravel directories found"
else
    print_error "Missing Laravel directories: ${missing_dirs[*]}"
    echo "Run: cp -r laravel-backend/* $WWW_DIR/"
fi

# Check .htaccess
print_status "Checking .htaccess..."
if [ ! -f "$WWW_DIR/.htaccess" ]; then
    print_error ".htaccess not found"
    echo "Run: cp laravel-backend-www.htaccess $WWW_DIR/.htaccess"
else
    print_success ".htaccess found"
    
    # Check if it has Laravel rewrite rules
    if grep -q "RewriteRule.*index.php" "$WWW_DIR/.htaccess"; then
        print_success ".htaccess has Laravel rewrite rules"
    else
        print_warning ".htaccess exists but may not have proper Laravel rewrite rules"
    fi
fi

# Check .env file
print_status "Checking .env file..."
if [ ! -f "$WWW_DIR/.env" ]; then
    print_warning ".env file not found"
    echo "Run: cp laravel-backend/.env.example $WWW_DIR/.env"
    echo "Then edit .env with production settings"
else
    print_success ".env file found"
    
    # Check if APP_KEY is set
    if grep -q "APP_KEY=" "$WWW_DIR/.env" && ! grep -q "APP_KEY=$" "$WWW_DIR/.env"; then
        print_success "APP_KEY appears to be set"
    else
        print_warning "APP_KEY not set in .env"
        echo "Run: cd $WWW_DIR && php artisan key:generate"
    fi
fi

# Check file permissions
print_status "Checking file permissions..."

# Check if we can read index.php
if [ -r "$WWW_DIR/index.php" ]; then
    print_success "index.php is readable"
else
    print_error "index.php is not readable"
    echo "Run: chmod 644 $WWW_DIR/index.php"
fi

# Check storage directory permissions
if [ -d "$WWW_DIR/storage" ]; then
    if [ -w "$WWW_DIR/storage" ]; then
        print_success "storage directory is writable"
    else
        print_error "storage directory is not writable"
        echo "Run: chmod -R 775 $WWW_DIR/storage"
    fi
fi

# Check bootstrap/cache permissions
if [ -d "$WWW_DIR/bootstrap/cache" ]; then
    if [ -w "$WWW_DIR/bootstrap/cache" ]; then
        print_success "bootstrap/cache directory is writable"
    else
        print_error "bootstrap/cache directory is not writable"
        echo "Run: chmod -R 775 $WWW_DIR/bootstrap/cache"
    fi
fi

# Test Laravel installation
print_status "Testing Laravel installation..."
cd "$WWW_DIR"

if command -v php &> /dev/null; then
    if php artisan --version &> /dev/null; then
        print_success "Laravel CLI working"
        php artisan --version
    else
        print_error "Laravel CLI not working"
        echo "This indicates Laravel is not properly installed"
        echo "Run: composer install --no-dev --optimize-autoloader"
    fi
else
    print_warning "PHP not found or not in PATH"
fi

# Check Apache modules (if possible)
print_status "Checking Apache configuration..."

if command -v apache2ctl &> /dev/null; then
    if apache2ctl -M 2>/dev/null | grep -q "rewrite"; then
        print_success "Apache mod_rewrite is enabled"
    else
        print_error "Apache mod_rewrite is not enabled"
        echo "Run: sudo a2enmod rewrite && sudo systemctl restart apache2"
    fi
else
    print_warning "Cannot check Apache modules (apache2ctl not found)"
fi

echo "=========================================="
echo "Diagnostic Complete"
echo "=========================================="

# Provide recommendations
echo ""
echo "RECOMMENDATIONS:"
echo ""

if [ ! -f "$WWW_DIR/index.php" ]; then
    echo "1. Copy Laravel files to www directory:"
    echo "   cp -r laravel-backend/* $WWW_DIR/"
fi

if [ ${#missing_dirs[@]} -gt 0 ]; then
    echo "2. Ensure all Laravel directories are present"
fi

if [ ! -f "$WWW_DIR/.htaccess" ]; then
    echo "3. Copy .htaccess file:"
    echo "   cp laravel-backend-www.htaccess $WWW_DIR/.htaccess"
fi

if [ ! -f "$WWW_DIR/.env" ]; then
    echo "4. Create .env file:"
    echo "   cp laravel-backend/.env.example $WWW_DIR/.env"
    echo "   cd $WWW_DIR && php artisan key:generate"
fi

echo "5. Fix permissions:"
echo "   chown -R www-data:www-data $WWW_DIR"
echo "   chmod -R 755 $WWW_DIR"
echo "   chmod -R 775 $WWW_DIR/storage"
echo "   chmod -R 775 $WWW_DIR/bootstrap/cache"

echo ""
echo "After fixing these issues, test your deployment:"
echo "https://www.yourdomain.com/"
echo "https://www.yourdomain.com/api/test"
