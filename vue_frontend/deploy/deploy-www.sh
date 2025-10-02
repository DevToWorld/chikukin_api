#!/bin/bash

# Laravel Backend Deployment Script for www Subdomain
# This script deploys the Laravel backend to the www directory

set -e  # Exit on any error

# Configuration
PROJECT_ROOT="/path/to/your/project"  # Update this path
WWW_DIR="/home/user/www"              # Update this path
LARAVEL_BACKEND_DIR="$PROJECT_ROOT/laravel-backend"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
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

# Check if directories exist
check_directories() {
    print_status "Checking directories..."
    
    if [ ! -d "$LARAVEL_BACKEND_DIR" ]; then
        print_error "Laravel backend directory not found: $LARAVEL_BACKEND_DIR"
        exit 1
    fi
    
    if [ ! -d "$WWW_DIR" ]; then
        print_warning "WWW directory does not exist: $WWW_DIR"
        print_status "Creating WWW directory..."
        mkdir -p "$WWW_DIR"
    fi
    
    print_success "Directories checked"
}

# Install/update Composer dependencies
install_dependencies() {
    print_status "Installing Composer dependencies..."
    
    cd "$LARAVEL_BACKEND_DIR"
    
    if [ ! -f "composer.json" ]; then
        print_error "composer.json not found in Laravel backend directory"
        exit 1
    fi
    
    # Install dependencies for production
    composer install --optimize-autoloader --no-dev --no-interaction
    
    print_success "Dependencies installed"
}

# Setup Laravel environment
setup_laravel() {
    print_status "Setting up Laravel environment..."
    
    cd "$LARAVEL_BACKEND_DIR"
    
    # Copy .env if it doesn't exist
    if [ ! -f ".env" ]; then
        if [ -f ".env.example" ]; then
            print_status "Creating .env from .env.example..."
            cp .env.example .env
            print_warning "Please update .env file with production settings!"
        else
            print_error ".env.example not found. Please create .env file manually."
            exit 1
        fi
    fi
    
    # Generate app key if not set
    if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
        print_status "Generating application key..."
        php artisan key:generate
    fi
    
    # Clear and cache configurations
    print_status "Caching configurations..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    print_success "Laravel environment setup complete"
}

# Copy files to www directory
deploy_files() {
    print_status "Deploying files to www directory..."
    
    # Create backup of existing www directory
    if [ -d "$WWW_DIR" ] && [ "$(ls -A $WWW_DIR)" ]; then
        print_status "Creating backup of existing www directory..."
        BACKUP_DIR="${WWW_DIR}_backup_$(date +%Y%m%d_%H%M%S)"
        cp -r "$WWW_DIR" "$BACKUP_DIR"
        print_success "Backup created: $BACKUP_DIR"
    fi
    
    # Copy Laravel backend files
    print_status "Copying Laravel backend files..."
    cp -r "$LARAVEL_BACKEND_DIR"/* "$WWW_DIR/"
    
    # Copy the www-specific .htaccess
    if [ -f "$PROJECT_ROOT/laravel-backend-www.htaccess" ]; then
        print_status "Copying www-specific .htaccess..."
        cp "$PROJECT_ROOT/laravel-backend-www.htaccess" "$WWW_DIR/.htaccess"
    else
        print_warning "laravel-backend-www.htaccess not found. Using default .htaccess"
    fi
    
    print_success "Files deployed to www directory"
}

# Set proper permissions
set_permissions() {
    print_status "Setting proper permissions..."
    
    # Set ownership
    chown -R www-data:www-data "$WWW_DIR"
    
    # Set directory permissions
    find "$WWW_DIR" -type d -exec chmod 755 {} \;
    
    # Set file permissions
    find "$WWW_DIR" -type f -exec chmod 644 {} \;
    
    # Special permissions for storage and cache
    chmod -R 775 "$WWW_DIR/storage"
    chmod -R 775 "$WWW_DIR/bootstrap/cache"
    
    # Make artisan executable
    chmod +x "$WWW_DIR/artisan"
    
    print_success "Permissions set"
}

# Run database migrations
run_migrations() {
    print_status "Running database migrations..."
    
    cd "$WWW_DIR"
    
    # Check if .env is configured
    if [ ! -f ".env" ]; then
        print_error ".env file not found in www directory"
        exit 1
    fi
    
    # Run migrations
    php artisan migrate --force
    
    print_success "Database migrations completed"
}

# Create storage link
create_storage_link() {
    print_status "Creating storage link..."
    
    cd "$WWW_DIR"
    
    # Remove existing link if it exists
    if [ -L "public/storage" ]; then
        rm "public/storage"
    fi
    
    # Create storage link
    php artisan storage:link
    
    print_success "Storage link created"
}

# Test deployment
test_deployment() {
    print_status "Testing deployment..."
    
    cd "$WWW_DIR"
    
    # Test Laravel configuration
    php artisan config:clear
    php artisan config:cache
    
    # Test if artisan works
    php artisan --version
    
    print_success "Deployment test completed"
}

# Main deployment function
main() {
    echo "=========================================="
    echo "Laravel Backend Deployment to www"
    echo "=========================================="
    
    # Check if running as root (optional)
    if [ "$EUID" -eq 0 ]; then
        print_warning "Running as root. Consider using a non-root user."
    fi
    
    # Update configuration paths if needed
    if [ "$PROJECT_ROOT" = "/path/to/your/project" ]; then
        print_error "Please update PROJECT_ROOT in the script with your actual project path"
        exit 1
    fi
    
    if [ "$WWW_DIR" = "/home/user/www" ]; then
        print_error "Please update WWW_DIR in the script with your actual www directory path"
        exit 1
    fi
    
    # Run deployment steps
    check_directories
    install_dependencies
    setup_laravel
    deploy_files
    set_permissions
    run_migrations
    create_storage_link
    test_deployment
    
    echo "=========================================="
    print_success "Deployment completed successfully!"
    echo "=========================================="
    echo ""
    echo "Next steps:"
    echo "1. Update your .env file with production settings"
    echo "2. Configure your web server to point to: $WWW_DIR"
    echo "3. Test your API endpoints"
    echo "4. Set up SSL certificate if needed"
    echo ""
    echo "API URL: https://www.yourdomain.com/api/"
    echo ""
}

# Help function
show_help() {
    echo "Laravel Backend Deployment Script for www Subdomain"
    echo ""
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -h, --help     Show this help message"
    echo "  --skip-deps    Skip Composer dependency installation"
    echo "  --skip-migrate Skip database migrations"
    echo "  --skip-test    Skip deployment testing"
    echo ""
    echo "Before running this script:"
    echo "1. Update PROJECT_ROOT and WWW_DIR variables in the script"
    echo "2. Ensure you have proper permissions to the target directories"
    echo "3. Make sure Composer and PHP are installed"
    echo ""
}

# Parse command line arguments
SKIP_DEPS=false
SKIP_MIGRATE=false
SKIP_TEST=false

while [[ $# -gt 0 ]]; do
    case $1 in
        -h|--help)
            show_help
            exit 0
            ;;
        --skip-deps)
            SKIP_DEPS=true
            shift
            ;;
        --skip-migrate)
            SKIP_MIGRATE=true
            shift
            ;;
        --skip-test)
            SKIP_TEST=true
            shift
            ;;
        *)
            print_error "Unknown option: $1"
            show_help
            exit 1
            ;;
    esac
done

# Override functions based on flags
if [ "$SKIP_DEPS" = true ]; then
    install_dependencies() {
        print_status "Skipping dependency installation"
    }
fi

if [ "$SKIP_MIGRATE" = true ]; then
    run_migrations() {
        print_status "Skipping database migrations"
    }
fi

if [ "$SKIP_TEST" = true ]; then
    test_deployment() {
        print_status "Skipping deployment testing"
    }
fi

# Run main function
main
