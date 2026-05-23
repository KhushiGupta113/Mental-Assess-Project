FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    zip \
    libzip-dev \
    libssl-dev \
    pkg-config \
    libcurl4-openssl-dev \
    libsasl2-dev \
    libzstd-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install zip

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy project
COPY . .

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Install node dependencies
RUN npm install

# Build frontend
RUN npm run build

# Create Laravel writable directories
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Give permissions
RUN chmod -R 775 storage bootstrap/cache

# Clear caches
RUN php artisan config:clear
# RUN php artisan cache:clear
# RUN php artisan route:clear
# RUN php artisan view:clear

EXPOSE 10000

# CMD php artisan serve --host=0.0.0.0 --port=10000
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]