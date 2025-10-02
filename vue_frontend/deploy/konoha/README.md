Konoha (ConoHa) Deployment Guide

Overview
- Target: Run Laravel backend (laravel-backend) on a ConoHa VPS.
- Web server: Nginx + PHP-FPM
- Database: MySQL (local or managed). Use `.env.konoha.example` as a base.

Server Setup (Ubuntu 22.04)
- Update packages: `sudo apt update && sudo apt upgrade -y`
- Install dependencies: `sudo apt install -y nginx php8.2 php8.2-fpm php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-sqlite3 php8.2-mysql unzip git mysql-client`
- Optional: `sudo apt install -y redis-server` and enable if needed.

App Deploy
- Clone repo to e.g. `/var/www/cri-app` and `cd /var/www/cri-app/laravel-backend`.
- Copy env: `cp .env.konoha.example .env` and set values (APP_KEY, DB_* etc.).
- Generate key: `php artisan key:generate`.
- Install vendors: `composer install --no-dev --optimize-autoloader` (ensure composer installed).
- Storage and cache: `php artisan storage:link && php artisan config:cache`.
- DB migrate: `php artisan migrate --force` (and optional `php artisan db:seed --force`).

Nginx
- Place `deploy/konoha/nginx.conf` into `/etc/nginx/sites-available/cri-app` and symlink to sites-enabled.
- Update root path inside config to the absolute path of `laravel-backend/public`.
- Test and reload: `sudo nginx -t && sudo systemctl reload nginx`.

PHP-FPM
- Optionally tune pool using `deploy/konoha/php-fpm-pool.conf` (set user/group and pm settings to match server load).
- Restart: `sudo systemctl restart php8.2-fpm`.

File Permissions
- `sudo chown -R www-data:www-data storage bootstrap/cache`
- `sudo find storage -type d -exec chmod 775 {} \;`
- `sudo find storage -type f -exec chmod 664 {} \;`

Scheduler/Queue
- Scheduler: `crontab -e` add `* * * * * cd /var/www/cri-app/laravel-backend && php artisan schedule:run >> /dev/null 2>&1`.
- Queue (if needed): use Supervisor or `php artisan queue:work --daemon`.

MySQL Data Transfer
- Use the new artisan helpers:
  - On source env: `php artisan transfer:export --connection=<source>`
  - Copy `storage/app/transfer/*.ndjson` to the server
  - On server (MySQL target): set `.env` DB to MySQL, then run `php artisan transfer:import --truncate`

Notes
- Keep this deploy prep separate; it does not change runtime behavior of existing environments.

