#!/bin/bash

# Check if vendor/autoload.php exists; if not, run composer install
if [ ! -f vendor/autoload.php ]; then
    composer install --no-progress --no-interaction
fi

# Check if .env exists; if not, create it
if [ ! -f .env ]; then
    echo "Creating .env file for environment $APP_ENV"
    cp .env.example .env
else
    echo ".env file already exists."
fi

# Determine container role
role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    php artisan migrate --force
    php artisan key:generate
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan serve --port=${PORT:-8000} --host=0.0.0.0 --env=.env
    exec docker-php-entrypoint "$@"
elif [ "$role" = "queue" ]; then
    echo "Running the queue ..."
    php /var/www/artisan queue:work --verbose --tries=3 --timeout=180
elif [ "$role" = "websocket" ]; then
    echo "Running the websocket server ..."
    php artisan websockets:serve
else
    echo "Invalid container role \"$role\""
    exit 1
fi
