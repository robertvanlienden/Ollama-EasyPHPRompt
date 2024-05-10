#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ]; then
  until nc -z -v -w30 database 3306
  do
    echo "Waiting for database connection..."
    sleep 1
  done

    if [ "$APP_ENV" != 'prod' ]; then
        composer install --prefer-dist --no-progress --no-suggest --no-interaction
        yarn install
        yarn run build
    fi

    composer run-script --no-dev post-install-cmd

  echo "FPM is running"

fi

exec docker-php-entrypoint "$@"
