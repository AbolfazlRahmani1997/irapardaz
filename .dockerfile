# Use the official PHP image
FROM php:8.1-cli

# Set the working directory in the container
WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Copy composer.lock and composer.json
COPY composer.lock composer.json /app/

# Install composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copy the rest of the application code
COPY . /app/

# Generate the autoload files
RUN composer dump-autoload --no-scripts --optimize
