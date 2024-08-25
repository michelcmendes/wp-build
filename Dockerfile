# Stage 1: Build the WordPress environment
FROM wordpress:php8.3-fpm AS builder

# Install any additional dependencies or PHP extensions here if needed
# RUN docker-php-ext-install <additional_extensions>

# Install PHPUnit
RUN curl -O https://phar.phpunit.de/phpunit-9.phar && \
    chmod +x phpunit-9.phar && \
    mv phpunit-9.phar /usr/local/bin/phpunit

WORKDIR /var/www/html

# (Optional) Copy the entire application codebase
# COPY . .

# Copy reelease manifest for release information
COPY manifest.json ./

# copy the test files
COPY tests/ ./tests/
COPY phpunit* ./

# Copy the development data to the container
# COPY wp-content/ ./wp-content/ 

# Set correct permissions for WordPress (if necessary)
RUN chown -R www-data:www-data /var/www/html

# Stage 2: Create the final image
FROM wordpress:php8.3-fpm AS production

WORKDIR /var/www/html

# Copy reelease manifest for release information
COPY manifest.json ./

# Copy the development data to the container
# COPY wp-content/ ./wp-content/ 

# Set correct permissions for WordPress (if necessary)
RUN chown -R www-data:www-data /var/www/html

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
