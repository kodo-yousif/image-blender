FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Run migrations + seed when container starts
CMD if [ ! -f database/database.sqlite ]; then \
      touch database/database.sqlite && \
      php artisan migrate && \
      php artisan db:seed --class=AdminUserSeeder; \
    fi && \
    php artisan serve --host=0.0.0.0 --port=4444
