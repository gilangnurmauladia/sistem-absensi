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

EXPOSE 80

# PERUBAHAN DI SINI: Menjaga container tetap hidup & berjalan di foreground
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]

