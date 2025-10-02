@echo off
REM Laravel Backend Deployment Script for www Subdomain (Windows)
REM This script deploys the Laravel backend to the www directory

setlocal enabledelayedexpansion

REM Configuration - UPDATE THESE PATHS
set PROJECT_ROOT=E:\2025\Jun\Vue\modeitest-main 6
set WWW_DIR=C:\xampp\htdocs\www
set LARAVEL_BACKEND_DIR=%PROJECT_ROOT%\laravel-backend

REM Colors (basic)
set INFO_COLOR=
set SUCCESS_COLOR=
set WARNING_COLOR=
set ERROR_COLOR=

echo ==========================================
echo Laravel Backend Deployment to www
echo ==========================================

REM Check if directories exist
echo [INFO] Checking directories...

if not exist "%LARAVEL_BACKEND_DIR%" (
    echo [ERROR] Laravel backend directory not found: %LARAVEL_BACKEND_DIR%
    pause
    exit /b 1
)

if not exist "%WWW_DIR%" (
    echo [WARNING] WWW directory does not exist: %WWW_DIR%
    echo [INFO] Creating WWW directory...
    mkdir "%WWW_DIR%"
)

echo [SUCCESS] Directories checked

REM Install Composer dependencies
echo [INFO] Installing Composer dependencies...

cd /d "%LARAVEL_BACKEND_DIR%"

if not exist "composer.json" (
    echo [ERROR] composer.json not found in Laravel backend directory
    pause
    exit /b 1
)

REM Install dependencies for production
composer install --optimize-autoloader --no-dev --no-interaction

echo [SUCCESS] Dependencies installed

REM Setup Laravel environment
echo [INFO] Setting up Laravel environment...

REM Copy .env if it doesn't exist
if not exist ".env" (
    if exist ".env.example" (
        echo [INFO] Creating .env from .env.example...
        copy ".env.example" ".env"
        echo [WARNING] Please update .env file with production settings!
    ) else (
        echo [ERROR] .env.example not found. Please create .env file manually.
        pause
        exit /b 1
    )
)

REM Generate app key if not set
php artisan key:generate

REM Clear and cache configurations
echo [INFO] Caching configurations...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo [SUCCESS] Laravel environment setup complete

REM Create backup of existing www directory
if exist "%WWW_DIR%" (
    for /f %%i in ('dir /b "%WWW_DIR%" 2^>nul') do (
        echo [INFO] Creating backup of existing www directory...
        set BACKUP_DIR=%WWW_DIR%_backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
        set BACKUP_DIR=!BACKUP_DIR: =0!
        xcopy "%WWW_DIR%" "!BACKUP_DIR!" /E /I /H /Y
        echo [SUCCESS] Backup created: !BACKUP_DIR!
        goto :deploy_files
    )
)

:deploy_files
REM Deploy files to www directory
echo [INFO] Deploying files to www directory...

REM Copy Laravel backend files
xcopy "%LARAVEL_BACKEND_DIR%\*" "%WWW_DIR%\" /E /H /Y

REM Copy the www-specific .htaccess
if exist "%PROJECT_ROOT%\laravel-backend-www.htaccess" (
    echo [INFO] Copying www-specific .htaccess...
    copy "%PROJECT_ROOT%\laravel-backend-www.htaccess" "%WWW_DIR%\.htaccess"
) else (
    echo [WARNING] laravel-backend-www.htaccess not found. Using default .htaccess
)

echo [SUCCESS] Files deployed to www directory

REM Run database migrations
echo [INFO] Running database migrations...

cd /d "%WWW_DIR%"

REM Check if .env is configured
if not exist ".env" (
    echo [ERROR] .env file not found in www directory
    pause
    exit /b 1
)

REM Run migrations
php artisan migrate --force

echo [SUCCESS] Database migrations completed

REM Create storage link
echo [INFO] Creating storage link...

REM Remove existing link if it exists
if exist "public\storage" (
    rmdir /s /q "public\storage"
)

REM Create storage link
php artisan storage:link

echo [SUCCESS] Storage link created

REM Test deployment
echo [INFO] Testing deployment...

REM Test Laravel configuration
php artisan config:clear
php artisan config:cache

REM Test if artisan works
php artisan --version

echo [SUCCESS] Deployment test completed

echo ==========================================
echo [SUCCESS] Deployment completed successfully!
echo ==========================================
echo.
echo Next steps:
echo 1. Update your .env file with production settings
echo 2. Configure your web server to point to: %WWW_DIR%
echo 3. Test your API endpoints
echo 4. Set up SSL certificate if needed
echo.
echo API URL: https://www.yourdomain.com/api/
echo.

pause
