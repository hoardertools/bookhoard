#!/bin/bash

# Early itterations used a file driver for sessions and sync for queues. This bit of code will replace the file driver with the database driver.
if [ -f "/app/config/.env" ]; then
  # Check if SESSION_DRIVER=file exists in the file
  if grep -q "SESSION_DRIVER=file" "/app/config/.env"; then
    # Replace SESSION_DRIVER=file with SESSION_DRIVER=database
    sed -i 's/SESSION_DRIVER=file/SESSION_DRIVER=database/' "/app/config/.env"
  fi
  if grep -q "QUEUE_DRIVER=sync" "/app/config/.env"; then
      # Replace SESSION_DRIVER=file with SESSION_DRIVER=database
      sed -i 's/QUEUE_DRIVER=sync/QUEUE_DRIVER=database/' "/app/config/.env"
    fi
fi


# Seed the .env file if there is no file present
if [ ! -f "/app/config/.env" ]; then
  cat /bookhoard/.env.example > /bookhoard/.env
  php artisan key:generate

  mv /bookhoard/.env /app/config/.env

  # If this file does not exist, it's likely that the application started for the first time. Allow at least 10 seconds for the database to be ready.
  echo "Waiting for the database to become ready"
  sleep 10

fi

cp /app/config/.env /bookhoard/.env
php artisan config:cache
service cron start

# Run PHP preparation commands
php artisan migrate
php artisan db:seed

touch /bookhoard/storage/logs/laravel.log
# Set permissions for logging folder
chmod -R 777 /bookhoard/storage

# Start supervisord and services
exec /usr/bin/supervisord -n -c /etc/supervisord.conf
