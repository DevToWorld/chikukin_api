#!/bin/bash

# Build and Deploy Script for Sakura Server
# Laravel API + Vue Frontend

set -e  # Exit on any error

# Configuration
DOMAIN="your-domain.com"
SERVER_USER="your-username"
SERVER_HOST="your-domain.com"
LOCAL_PROJECT_ROOT="."
LARAVEL_BACKEND_PATH="laravel-backend"
VUE_FRONTEND_PATH="."
REMOTE_WWW_PATH="/home/$SERVER_USER/www"
REMOTE_PUBLIC_HTML_PATH="$REMOTE_WWW_PATH/public_html"
REMOTE_LARAVEL_PATH="$REMOTE_WWW_PATH/laravel-backend"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Check if required tools are installed
check_requirements() {
    log "Checking requirements..."
    
    if ! command -v npm &> /dev/null; then
        error "npm is not installed. Please install Node.js and npm."
    fi
    
    if ! command -v composer &> /dev/null; then
        error "composer is not installed. Please install Composer."
    fi
    
    if ! command -v rsync &> /dev/null; then
        error "rsync is not installed. Please install rsync."
    fi
    
    success "All requirements are met."
}

# Build Vue frontend
build_vue_frontend() {
    log "Building Vue frontend..."
    
    cd "$VUE_FRONTEND_PATH"
    
    # Install dependencies
    log "Installing npm dependencies..."
    npm install
    
    # Update API configuration for production
    log "Updating API configuration for production..."
    if [ -f "src/config/api.js" ]; then
        # Backup original file
        cp src/config/api.js src/config/api.js.backup
        
        # Update production URL
        sed -i.bak "s|baseURL: 'http://linkinc.sakura.ne.jp/'|baseURL: 'https://$DOMAIN/api'|g" src/config/api.js
        rm src/config/api.js.bak
    fi
    
    # Build for production
    log "Building Vue app for production..."
    npm run build
    
    if [ ! -d "dist" ]; then
        error "Vue build failed. No dist directory found."
    fi
    
    success "Vue frontend built successfully."
    cd "$LOCAL_PROJECT_ROOT"
}

# Prepare Laravel backend
prepare_laravel_backend() {
    log "Preparing Laravel backend..."
    
    cd "$LARAVEL_BACKEND_PATH"
    
    # Install/update dependencies
    log "Installing Composer dependencies..."
    composer install --optimize-autoloader --no-dev
    
    # Clear and rebuild caches
    log "Optimizing Laravel..."
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    success "Laravel backend prepared successfully."
    cd "$LOCAL_PROJECT_ROOT"
}

# Deploy to server
deploy_to_server() {
    log "Deploying to server..."
    
    # Create remote directories if they don't exist
    log "Creating remote directories..."
    ssh "$SERVER_USER@$SERVER_HOST" "mkdir -p $REMOTE_PUBLIC_HTML_PATH $REMOTE_LARAVEL_PATH"
    
    # Deploy Vue frontend (dist folder contents)
    log "Deploying Vue frontend..."
    rsync -avz --delete dist/ "$SERVER_USER@$SERVER_HOST:$REMOTE_PUBLIC_HTML_PATH/"
    
    # Deploy Laravel backend
    log "Deploying Laravel backend..."
    rsync -avz --delete \
        --exclude='.git' \
        --exclude='node_modules' \
        --exclude='tests' \
        --exclude='.env' \
        --exclude='storage/logs/*' \
        --exclude='storage/framework/cache/*' \
        --exclude='storage/framework/sessions/*' \
        --exclude='storage/framework/views/*' \
        "$LARAVEL_BACKEND_PATH/" "$SERVER_USER@$SERVER_HOST:$REMOTE_LARAVEL_PATH/"
    
    # Deploy .htaccess file
    log "Deploying .htaccess configuration..."
    scp .htaccess "$SERVER_USER@$SERVER_HOST:$REMOTE_PUBLIC_HTML_PATH/"
    
    # Update .htaccess with correct paths
    ssh "$SERVER_USER@$SERVER_HOST" "sed -i 's|/home/your-username/www/laravel-backend|$REMOTE_LARAVEL_PATH|g' $REMOTE_PUBLIC_HTML_PATH/.htaccess"
    ssh "$SERVER_USER@$SERVER_HOST" "sed -i 's|https://your-domain.com|https://$DOMAIN|g' $REMOTE_PUBLIC_HTML_PATH/.htaccess"
    
    success "Deployment completed successfully."
}

# Set permissions on server
set_server_permissions() {
    log "Setting server permissions..."
    
    ssh "$SERVER_USER@$SERVER_HOST" "
        # Set Laravel permissions
        chmod -R 755 $REMOTE_LARAVEL_PATH/storage
        chmod -R 755 $REMOTE_LARAVEL_PATH/bootstrap/cache
        chown -R www-data:www-data $REMOTE_LARAVEL_PATH/storage
        chown -R www-data:www-data $REMOTE_LARAVEL_PATH/bootstrap/cache
        
        # Set .htaccess permissions
        chmod 644 $REMOTE_PUBLIC_HTML_PATH/.htaccess
        
        # Set .env permissions (if exists)
        if [ -f $REMOTE_LARAVEL_PATH/.env ]; then
            chmod 600 $REMOTE_LARAVEL_PATH/.env
        fi
    "
    
    success "Server permissions set successfully."
}

# Test deployment
test_deployment() {
    log "Testing deployment..."
    
    # Test frontend
    log "Testing frontend..."
    if curl -s -o /dev/null -w "%{http_code}" "https://$DOMAIN" | grep -q "200"; then
        success "Frontend is accessible."
    else
        warning "Frontend test failed. Check manually."
    fi
    
    # Test API
    log "Testing API..."
    if curl -s -o /dev/null -w "%{http_code}" "https://$DOMAIN/api" | grep -q "200\|404"; then
        success "API is accessible."
    else
        warning "API test failed. Check manually."
    fi
    
    log "Deployment test completed. Please verify manually at https://$DOMAIN"
}

# Cleanup function
cleanup() {
    log "Cleaning up..."
    
    # Restore original API config if backup exists
    if [ -f "$VUE_FRONTEND_PATH/src/config/api.js.backup" ]; then
        mv "$VUE_FRONTEND_PATH/src/config/api.js.backup" "$VUE_FRONTEND_PATH/src/config/api.js"
        log "Restored original API configuration."
    fi
}

# Main deployment function
main() {
    log "Starting deployment process..."
    
    # Trap to ensure cleanup on exit
    trap cleanup EXIT
    
    # Check requirements
    check_requirements
    
    # Build and prepare
    build_vue_frontend
    prepare_laravel_backend
    
    # Deploy
    deploy_to_server
    set_server_permissions
    
    # Test
    test_deployment
    
    success "Deployment completed successfully!"
    log "Your application is now live at: https://$DOMAIN"
    log "API endpoints are available at: https://$DOMAIN/api/*"
}

# Show usage if no arguments
if [ $# -eq 0 ]; then
    echo "Usage: $0 [options]"
    echo ""
    echo "Options:"
    echo "  --build-only     Only build, don't deploy"
    echo "  --deploy-only    Only deploy, don't build"
    echo "  --test-only      Only test deployment"
    echo "  --help           Show this help message"
    echo ""
    echo "Configuration:"
    echo "  Edit this script to set DOMAIN, SERVER_USER, and SERVER_HOST"
    exit 0
fi

# Parse arguments
case "$1" in
    --build-only)
        check_requirements
        build_vue_frontend
        prepare_laravel_backend
        success "Build completed successfully!"
        ;;
    --deploy-only)
        check_requirements
        deploy_to_server
        set_server_permissions
        test_deployment
        success "Deployment completed successfully!"
        ;;
    --test-only)
        test_deployment
        ;;
    --help)
        echo "Usage: $0 [options]"
        echo "See script for configuration details."
        ;;
    *)
        main
        ;;
esac
