#!/bin/sh

cd /var/www

# php artisan cache:clear
php artisan config:clear
php artisan route:cache
php artisan migrate:fresh --seed

/usr/bin/supervisord -c /etc/supervisord.conf
