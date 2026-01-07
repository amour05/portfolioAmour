FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=$PORT"]
