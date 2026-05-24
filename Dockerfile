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
    libzip-dev \
    libicu-dev \
    pkg-config \
    autoconf \
    g++ \
    make \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions for Laravel
RUN docker-php-ext-install \
    pdo \
    mbstring \
    exif \
    pcntl \
    bcmath \
    xml \
    zip \
    intl \
    opcache

# Install MongoDB PHP extension via PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for Docker layer caching)
COPY composer.json composer.lock ./

# Install PHP dependencies (ignore platform reqs since extensions are already installed above)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --ignore-platform-reqs

# Copy the rest of the application
COPY . .

# Generate optimized autoload files
RUN COMPOSER_MEMORY_LIMIT=-1 composer dump-autoload --optimize --no-scripts --ignore-platform-reqs

# Set proper storage/cache permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Hardcode environment variables for auto-deployment (Railway/Render)
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:mPNGUtfIvUnqAmi37gYDm64z1Sr8TDUQd4QyUrB1/cE=
ENV DB_CONNECTION=mongodb
ENV MONGODB_URI="mongodb+srv://arijitdebnath:ari123jit@cluster.vk5qq1f.mongodb.net/entreconnect?retryWrites=true&w=majority&appName=Cluster"
ENV SESSION_DRIVER=file
ENV CACHE_DRIVER=file
ENV QUEUE_CONNECTION=sync
ENV LOG_CHANNEL=stderr

# Expose the port
EXPOSE 8000

# Start Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
