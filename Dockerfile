# Image PHP avec Composer
FROM php:8.2-cli

# Installer les extensions nécessaires pour MySQL
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le projet
WORKDIR /app
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Donner les bons droits à Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Exposer le port
EXPOSE 8000

# Lancer le serveur Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
