#!/bin/bash

# Start Nginx
service nginx start

# Run migrations
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Handle APP_KEY
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY env var is empty, checking .env file..."
    if grep -q "APP_KEY=" .env; then
         VAL=$(grep "APP_KEY=" .env | cut -d '=' -f2)
         if [ -z "$VAL" ]; then
            echo "APP_KEY in .env is also empty. Generating..."
            NEW_KEY=$(php key_generate_script.php)
            # Use sed to replace the empty key or append if missing
            sed -i "s|^APP_KEY=.*|APP_KEY=$NEW_KEY|" .env || echo "APP_KEY=$NEW_KEY" >> .env
            export APP_KEY=$NEW_KEY
            echo "Generated new APP_KEY."
         else
            echo "Found APP_KEY in .env, using it."
            export APP_KEY=$VAL
         fi
    else
        echo "APP_KEY not in .env. Generating..."
        NEW_KEY=$(php key_generate_script.php)
        echo "APP_KEY=$NEW_KEY" >> .env
        export APP_KEY=$NEW_KEY
        echo "Generated and appended APP_KEY."
    fi
else
    echo "APP_KEY present in environment ($APP_KEY), ensuring it is in .env..."
    # Ensure it's in .env for Artisan commands content seeing it
    if grep -q "APP_KEY=" .env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env
    else
        echo "APP_KEY=$APP_KEY" >> .env
    fi
fi

php artisan config:clear

php artisan config:clear
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Start PHP-FPM
php-fpm
