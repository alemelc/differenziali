#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Only generate key if not set in environment
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY not set, generating..."
    export APP_KEY=$(php key_generate_script.php)
    echo "APP_KEY generated: $APP_KEY"
    # Append to .env just in case
    echo "APP_KEY=$APP_KEY" >> .env
else
    echo "APP_KEY present in environment, skipping generation."
fi

php artisan config:clear

php artisan config:clear
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start PHP-FPM
php-fpm
