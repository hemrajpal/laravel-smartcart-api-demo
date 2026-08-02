# ==========================================
# STAGE 1: Build & Dependency Installation
# ==========================================
FROM php:8.4-alpine AS builder

# Install system utilities needed ONLY for Composer install
RUN apk add --no-cache git unzip curl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install Laravel 12 production dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-interaction --optimize-autoloader --no-dev

# ==========================================
# STAGE 2: Final Stable Production Image
# ==========================================
# CHANGED: Swapped from apache-alpine to official debian-apache base
FROM php:8.4-apache

# Install runtime dependencies & PHP extensions for Laravel 12
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql gd zip bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Crucial for Render: Route Apache to Laravel 12's /public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copy your optimized code and dependencies from Stage 1
COPY --from=builder /app /var/www/html

# Set correct runtime permissions for Laravel 12 storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Render injects the PORT env variable automatically (defaults to 80)
EXPOSE 80

# AUTOMATIC MIGRATION LINE: Runs migrations first, then runs the Apache server
CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    apache2-foreground
