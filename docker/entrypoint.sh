#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
php artisan config:clear
php artisan key:generate --force
php artisan config:clear
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start PHP-FPM
php-fpm
