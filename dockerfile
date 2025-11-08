FROM php:8.3-cli-bullseye

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    unzip zip curl git libzip-dev libpng-dev libonig-dev libxml2-dev \
    libcrypt-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/laravel