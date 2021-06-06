#!/bin/sh

composer install --prefer-dist --no-progress --no-suggest --working-dir="/app"

exec php-fpm