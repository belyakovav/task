FROM php:8.1.0-fpm-alpine

# install packages
RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash
#mysql
RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_mysql

# non-root user.
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

# source code
RUN chown www-data:www-data /var/www/
COPY --chown=www-data:www-data ./ /var/www
WORKDIR /var/www

USER www-data

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000
