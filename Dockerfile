FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

CMD ["php-fpm"]

