FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    shadow \
    bzip2-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install PHP dependencies (Hemat RAM & Tanpa Dev)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Setup Nginx configuration
RUN mkdir -p /run/nginx
COPY config/railway/nginx.conf /etc/nginx/nginx.conf

# PERUBAHAN BARU: Berikan izin penuh pada folder storage Laravel agar tidak crash
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]

