FROM php:8.3-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip

# install composer
COPY --from=composer:2.7.7 /usr/bin/composer /usr/local/bin/composer

# Set the working directory
COPY --chown=www-data:www-data . /var/www/app
WORKDIR /var/www/app

# copy composer.json to workdir & install dependencies
RUN export COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --optimize-autoloader --no-dev \
    && php artisan key:generate --force \
    && php artisan migrate --force \
    && php artisan optimize:clear \
    && php artisan config:cache \
    && php artisan event:cache \
    && php artisan route:cache \
    && php artisan view:cache

RUN chgrp -R www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache \
    && chmod -R 775 /var/www/app/storage

# Set the default command to run php-fpm
CMD ["php-fpm"]
