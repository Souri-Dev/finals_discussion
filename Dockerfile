FROM php:8.3-fpm

WORKDIR /app

# Install system deps
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git unzip zip libicu-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip \
    && rm -rf /var/lib/apt/lists/*

# Allow Composer plugins (FIX for Symfony Flex error)
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Install dependencies (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

COPY docker/php.ini /usr/local/etc/php/php.ini

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Permissions (important for Symfony)
RUN chown -R www-data:www-data var

# Copy entrypoint script
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

# Run entrypoint (cache warmup + supervisord)
CMD ["/usr/local/bin/entrypoint.sh"]