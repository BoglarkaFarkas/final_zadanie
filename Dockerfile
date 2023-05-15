FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

