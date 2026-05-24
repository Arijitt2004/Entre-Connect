FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    pkg-config \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo mbstring exif pcntl bcmath

# Install MongoDB PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . .

# Run post-install scripts
RUN composer run-script post-autoload-dump || true

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cache Laravel configs for production
RUN php artisan config:cache || true \
    && php artisan route:cache || true \
    && php artisan view:cache || true

# Expose port
EXPOSE 8000

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
