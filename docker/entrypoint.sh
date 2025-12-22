#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi
php artisan migrate --force

# Cache config and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm
