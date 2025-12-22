#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
php artisan config:clear
php artisan key:generate --force
php artisan config:clear
php artisan migrate --force

# Cache config and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm
