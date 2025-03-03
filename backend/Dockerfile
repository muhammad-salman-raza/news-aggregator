# backend/Dockerfile

############################
# ARG for Environment Setup
############################
# Default to production if not provided.
ARG APP_ENV=production

############################
# Stage 1: Builder
############################
FROM composer:2.3 AS builder
ARG APP_ENV

WORKDIR /app

# Copy composer files and install dependencies
# If in production, skip dev dependencies and optimize autoloader;
# otherwise install all packages for development (including testing and quality tools).
COPY . .
RUN if [ "$APP_ENV" = "production" ]; then \
      composer install --no-dev --optimize-autoloader --prefer-dist; \
    else \
      composer install; \
    fi

# Copy the rest of the application code
COPY . .

# (Optional) Cache configuration and routes in production for performance.
RUN if [ "$APP_ENV" = "production" ]; then \
      php artisan config:cache && php artisan route:cache; \
    fi

############################
# Stage 2: Final Image
############################
FROM php:8.2-fpm-alpine
ARG APP_ENV

# Install system dependencies and required PHP extensions
RUN apk update && apk add --no-cache \
    libpng \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    postgresql-dev \
    zip \
    unzip \
  && docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd


# Install Composer in the final container
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install required PHP extensions
RUN docker-php-ext-install pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd

# In development, install Xdebug along with linux-headers for proper build support.
RUN if [ "$APP_ENV" != "production" ]; then \
      apk add --no-cache $PHPIZE_DEPS linux-headers && \
      pecl install xdebug && \
      docker-php-ext-enable xdebug; \
    fi

WORKDIR /var/www


# Copy the built application from the builder stage
COPY --from=builder /app /var/www

# Copy the entrypoint script and ensure it's executable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Ensure storage and cache directories are writable
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose the PHP-FPM port
EXPOSE 9000

# Set the entrypoint so that it runs our startup commands before executing CMD
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Use a CMD that works for your setup:
# For production with PHP-FPM (usually behind a web server)
CMD ["php-fpm"]
