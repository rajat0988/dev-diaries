#!/bin/bash

# Deploy Script for Laravel Application with Nginx
# This script automates the deployment process

set -e

echo "🚀 Starting deployment..."

# Pull latest changes from repository
echo "📥 Pulling latest changes from Git..."
git pull

# Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install/Update NPM dependencies and build assets
echo "📦 Installing NPM dependencies..."
npm ci --only=production

echo "🔨 Building assets..."
npm run build

# Put application in maintenance mode
echo "🔒 Enabling maintenance mode..."
php artisan down --retry=60 || true

# Run database migrations (only new ones, won't reset existing data)
echo "🗄️  Running database migrations..."
php artisan migrate --force --no-interaction

# Clear and cache configuration
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

echo "💾 Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize Composer autoloader
echo "⚡ Optimizing Composer autoloader..."
composer dump-autoload --optimize --no-dev --classmap-authoritative

# Set proper permissions
echo "🔐 Setting permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Clear OPcache (if available)
echo "🗑️  Clearing OPcache..."
php artisan optimize:clear || true

# Restart PHP-FPM
echo "🔄 Restarting PHP-FPM..."
sudo systemctl restart php8.2-fpm

# Reload Nginx (reload is better than restart for zero downtime)
echo "🔄 Reloading Nginx..."
sudo systemctl reload nginx

# Bring application back online
echo "🔓 Disabling maintenance mode..."
php artisan up

echo "✅ Deployment completed successfully!"
echo "📊 Application is now live!"

# Optional: Display Laravel version
php artisan --version
