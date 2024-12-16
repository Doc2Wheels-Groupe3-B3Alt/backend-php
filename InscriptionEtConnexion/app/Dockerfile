FROM php:8.1-apache

# Installer les extensions nécessaires pour PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activer le module rewrite d'Apache (utile pour PHP)
RUN a2enmod rewrite

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html