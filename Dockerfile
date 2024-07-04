FROM php:8.3-fpm-alpine

# Installer les dépendances système et PHP nécessaires
RUN apk add --no-cache \
    bash \
    git \
    zip \
    unzip \
    libzip-dev \
    postgresql-dev \
    && docker-php-ext-install zip pdo pdo_pgsql

# Installer Composer globalement
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier le fichier composer.json et composer.lock (si existant)
COPY composer.json composer.lock* ./

# Installer les dépendances PHP avec Composer uniquement si composer.json existe
RUN if [ -f composer.json ]; then composer install --prefer-dist --no-interaction; fi
