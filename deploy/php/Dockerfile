FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    curl-dev \
    git \
    unzip \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-install pdo_mysql mysqli mbstring gd zip

RUN #curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/

WORKDIR /var/www

RUN composer install --no-dev --optimize-autoloader