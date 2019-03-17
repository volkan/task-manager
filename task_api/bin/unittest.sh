#!/bin/bash

cp -R .env.dev .env

composer install

bin/console doctrine:database:create

bin/console doctrine:schema:update --force

bin/console doctrine:fixtures:load --append

bin/phpunit --coverage-text