FROM composer:2.0 AS build
COPY . /app/
RUN composer install --prefer-dist --no-dev --optimize-autoloader --no-interaction

FROM php:8.0-apache-buster AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

RUN docker-php-ext-configure opcache --enable-opcache && \
    docker-php-ext-install pdo pdo_mysql && \
    a2enmod rewrite

COPY docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY --from=build /app /var/www/html
COPY .env.prod /var/www/html/.env

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan storage:link

USER root
