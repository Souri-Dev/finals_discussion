FROM php:8.3-fpm

WORKDIR /app

# Install system deps
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git unzip zip libicu-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Install dependencies (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Symfony cache warmup
RUN php bin/console cache:clear --env=prod || true
RUN php bin/console cache:warmup --env=prod || true

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY docker/php.ini /usr/local/etc/php/php.ini

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Permissions (important for Symfony)
RUN chown -R www-data:www-data var

EXPOSE 80

CMD ["/usr/bin/supervisord", "-n"]