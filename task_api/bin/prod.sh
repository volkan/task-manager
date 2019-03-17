#!/bin/bash

cd /var/www/symfony

cp -R .env.prod .env

composer install --optimize-autoloader
#composer install --no-dev --optimize-autoloader
bin/console doctrine:database:create
bin/console doctrine:schema:update --force

APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

echo "!!!!!! Everything is ready to use !!!!!!"