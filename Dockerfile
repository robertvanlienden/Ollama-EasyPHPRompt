ARG ALPINE_VERSION=3.16
ARG NODE_VERSION=20

FROM node:$NODE_VERSION-alpine$ALPINE_VERSION AS node
FROM php:8.2-fpm-alpine$ALPINE_VERSION AS php-fpm

RUN apk update && apk upgrade\
   wget

RUN apk add libpq-dev --no-cache
RUN apk add wget curl git php php-curl php-openssl php-json php-phar php-dom php-intl --update
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

ENV COMPOSER_ALLOW_SUPERUSER=1

ENV APP_ENV=prod

ENV GD_DEPS freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev libwebp-dev
ENV INTL_DEPS icu-dev
ENV GIT_DEPS git
ENV XSL_DEPS libxslt-dev
ENV TIDY_DEPS tidyhtml-dev
ENV ZIP_DEPS zlib-dev libzip-dev
ENV SQL_DEPS mysql-client
ENV UNIX_DEPS bash
ENV MAKE_DEPS make

RUN set -xe \
    && apk add --no-cache $GIT_DEPS $GD_DEPS $INTL_DEPS $XSL_DEPS $TIDY_DEPS $ZIP_DEPS $SQL_DEPS $UNIX_DEPS $MAKE_DEPS \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-configure gd  --with-jpeg=/usr/include/ --with-freetype=/usr/include/ --with-webp=/usr/include/ \
    && docker-php-ext-install -j$(nproc) bcmath gd intl pdo pdo_mysql soap tidy xsl zip

COPY docker/fpm/development.ini $PHP_INI_DIR/conf.d/

WORKDIR /app

COPY composer.json composer.lock ./

RUN set -eux; \
    composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
    composer clear-cache

RUN apk add --no-cache python3 libstdc++ g++
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

COPY package.json yarn.lock webpack.config.js ./

COPY assets assets/
COPY config config/
COPY migrations migrations/
COPY bin bin/
COPY public public/
COPY src src/
COPY templates templates/
COPY .env.docker .env

RUN npm install -g yarn
RUN yarn install
RUN composer install --no-dev --no-progress

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative --no-dev; \
    chmod +x bin/console; \
    bin/console assets:install public; \
    yarn run build; \
    sync

RUN rm -rf node_modules

COPY docker/fpm/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM php-fpm AS deploy

ENV APP_ENV=prod

RUN apk add --no-cache openssh-client rsync
COPY deployer.phar deploy.php ./

FROM php-fpm AS php-fpm-test

RUN set -eux; \
    composer install --dev --prefer-dist --no-scripts --no-progress; \
    composer clear-cache

FROM php-fpm-test AS php-fpm-dev

ARG ENABLE_XDEBUG=true
ENV APP_ENV=dev

RUN set -eux; \
    if [ "$ENABLE_XDEBUG" = "true" ]; then \
        apk add linux-headers --no-cache $PHPIZE_DEPS --virtual .build-deps; \
        pecl install xdebug; \
        docker-php-ext-enable xdebug; \
        apk del .build-deps; \
    fi;

RUN set -eux; \
    if [ "$ENABLE_XDEBUG" = "true" ]; then \
        HOST_IP="$(/sbin/ip route|awk '/default/ { print $3 }')"; \
        echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini; \
        echo "xdebug.client_host=${HOST_IP}" >> /usr/local/etc/php/conf.d/xdebug.ini; \
        echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    fi;
