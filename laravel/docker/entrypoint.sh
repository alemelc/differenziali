#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
php artisan migrate --force

# Cache config and routes
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
php-fpm
