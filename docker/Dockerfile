FROM php:7.3-fpm-alpine

RUN apk add --no-cache \
    git \
    libzip

RUN apk add --no-cache --virtual .build-deps \
    libxml2-dev \
    autoconf \
    gcc \
    musl-dev \
    make \
    pcre-dev \
    libzip-dev \
 && docker-php-ext-install \
    bcmath \
    sockets \
    pcntl \
    mbstring \
    xml \
    zip \
    opcache \
 && apk del .build-deps

WORKDIR /var/www/app
ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./composer.* /var/www/app/

RUN curl -sS https://getcomposer.org/installer | php \
 && chmod +x composer.phar \
 && php composer.phar install --no-interaction --no-scripts

RUN ./vendor/bin/rr get-binary -l /usr/local/bin

COPY ./bin /var/www/app/bin
COPY ./config /var/www/app/config
COPY ./public /var/www/app/public
COPY ./roadrunner /var/www/app/roadrunner
COPY ./src /var/www/app/src
COPY ./.env /var/www/app/.env
RUN cd /var/www/app \
 && mkdir var \
 && chmod 0755 var \
 && chown www-data:www-data var

COPY ./docker/php.ini /usr/local/etc/php/php.ini

RUN php composer.phar dump-autoload --optimize --classmap-authoritative

EXPOSE 80

# RoadRunner expects SIGTERM signal
# to gracefully shutdown workers
STOPSIGNAL SIGTERM

CMD ["rr", "--help"]