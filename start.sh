#!/bin/sh
set -e
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
  touch /var/www/html/database/database.sqlite
fi
chown -R www-data:www-data /var/www/html/database
php artisan migrate --force
exec php artisan serve --host=0.0.0.0 --port=8080
