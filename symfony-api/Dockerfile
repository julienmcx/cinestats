FROM php:8.3-apache

# Autorise les plugins Composer (symfony/runtime, symfony/flex) à tourner en root
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update \
 && apt-get install -y libsqlite3-dev unzip git \
 && docker-php-ext-install pdo_sqlite \
 && rm -rf /var/lib/apt/lists/*

RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" \
 && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && rm /tmp/composer-setup.php \
 && composer --version

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --optimize-autoloader \
 && mkdir -p var \
 && php bin/console doctrine:schema:create --no-interaction \
 && chown -R www-data:www-data var

EXPOSE 80
