#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Manually generate key and write to .env
php key_generate_script.php

php artisan config:clear

php artisan config:clear
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start PHP-FPM
php-fpm
