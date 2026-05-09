FROM php:8.3-fpm

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    unzip \
    zip \
    curl \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP-FPM to listen on TCP 9000
RUN sed -i 's|listen = .*|listen = 9000|' /usr/local/etc/php-fpm.d/www.conf

# Allow Composer plugins
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# Symfony production environment
ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV SYMFONY_DOTENV_VARS=0

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Ensure .env exists
RUN touch .env && chmod 644 .env

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copy configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Symfony writable directories
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

# Copy entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

# Start container
CMD ["/usr/local/bin/entrypoint.sh"]