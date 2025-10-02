#!/bin/bash

# Quick Deploy Script for Sakura Server
# For frequent updates and testing

set -e

# Configuration - Update these values
DOMAIN="your-domain.com"
SERVER_USER="your-username"
SERVER_HOST="your-domain.com"

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m'

log() {
    echo -e "${BLUE}[$(date +'%H:%M:%S')]${NC} $1"
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

# Quick build and deploy
log "Quick deployment starting..."

# Build Vue frontend
log "Building Vue frontend..."
npm run build

# Deploy frontend
log "Deploying frontend..."
rsync -avz --delete dist/ "$SERVER_USER@$SERVER_HOST:~/www/public_html/"

# Deploy Laravel backend (if changed)
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
    laravel-backend/ "$SERVER_USER@$SERVER_HOST:~/www/laravel-backend/"

# Set permissions
log "Setting permissions..."
ssh "$SERVER_USER@$SERVER_HOST" "
    chmod -R 755 ~/www/laravel-backend/storage
    chmod -R 755 ~/www/laravel-backend/bootstrap/cache
    chown -R www-data:www-data ~/www/laravel-backend/storage
    chown -R www-data:www-data ~/www/laravel-backend/bootstrap/cache
"

success "Quick deployment completed!"
log "Check your site at: https://$DOMAIN"
