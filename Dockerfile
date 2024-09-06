FROM php:8.3-apache

# Installation des dépendances
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install zip pdo_pgsql

# Enable Apache modules
RUN a2enmod rewrite

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Installation des dépendances du projet (si nécessaire)
WORKDIR /var/www/html
COPY . /var/www/html

RUN if [ -f composer.json ] && [ -f composer.lock ]; then composer install --optimize-autoloader; fi

# Commande par défaut
CMD ["apache2-foreground"]
