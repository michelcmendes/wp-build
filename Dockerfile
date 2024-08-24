# Stage 1: Build the WordPress environment
FROM php:8.2-fpm-alpine as builder

# Install dependencies
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install Composer for dependency management
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy the Composer configuration
COPY composer.json ./

# Install WordPress dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the WordPress core files
# COPY . .

# Stage 2: Create the final image
FROM php:8.2-fpm-alpine

# Copy only necessary files from the build stage
COPY --from=builder /var/www/html /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]

