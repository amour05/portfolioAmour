# Stage 1 : build assets avec Node
FROM node:18-alpine AS node-builder
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources resources
RUN npm ci
RUN npm run build \
    && mkdir -p /app/public \
    && if [ -d /app/dist ]; then mv /app/dist /app/public/build; \
       elif [ -d /app/public/build ]; then echo "assets already in /app/public/build"; \
       else echo "No build output found"; fi

# Stage 2 : image PHP finale
FROM php:8.2-cli
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
# Copier tout le code source
COPY . .

# Copier les assets buildés depuis node-builder vers public/build
COPY --from=node-builder /app/public/build ./public/build

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Droits et nettoyage
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=$PORT"]
