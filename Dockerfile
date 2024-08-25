# Stage 1: Build the WordPress environment
FROM php:8.2-fpm-alpine AS builder

# Install dependencies for WordPress
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    mysql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install Composer for dependency management
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# (Optional) Copy the entire application codebase
# COPY . .

# Copy reelease manifest for release information
COPY manifest.json ./

# Copy the Composer configuration and install dependencies
COPY composer.json ./
RUN composer install --optimize-autoloader

# copy the test files
COPY tests/ ./tests/
COPY phpunit* ./


# Stage 2: Create the final image
FROM php:8.2-fpm-alpine AS production

# Copy only necessary files from the build stage
COPY --from=builder /var/www/html /var/www/html

# Copy the Composer configuration and install dependencies
COPY composer.json .
RUN composer install --no-dev --optimize-autoloader

# Set the working directory
WORKDIR /var/www/html

# Set correct permissions for WordPress (if necessary)
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
