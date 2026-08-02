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
# STAGE 2: Final Tiny Production Image
# ==========================================
FROM php:8.4-apache-alpine

# Install bare minimum runtime dependencies & PHP extensions for Laravel 12
RUN apk add --no-cache \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql gd zip bcmath opcache

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite 2>/dev/null || sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /etc/apache2/httpd.conf

# Crucial for Render: Route Apache to Laravel 12's /public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/localhost/htdocs!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/httpd.conf

WORKDIR /var/www/html

# Copy your optimized code and dependencies from Stage 1
COPY --from=builder /app /var/www/html

# Set correct runtime permissions for Laravel 12 storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Render injects the PORT env variable automatically (defaults to 80)
EXPOSE 80

# AUTOMATIC MIGRATION LINE: Runs migrations first, then runs the Apache server
CMD php artisan migrate --force && apache2-foreground
