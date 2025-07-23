FROM php:8.3-fpm
# Install dependencies
RUN apt-get update && apt-get install -y libzip-dev \
   build-essential \
   libpng-dev \
   libjpeg-dev \
   libfreetype6-dev \
   zip \
   unzip \
   git \
   libicu-dev \
   && docker-php-ext-configure gd --with-freetype --with-jpeg \
   && docker-php-ext-install gd pdo pdo_mysql intl zip
# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Set working directory
WORKDIR /var/www
# Copy composer files
COPY composer.json composer.lock ./
# Install dependencies
RUN composer install --no-scripts --no-autoloader
# Copy application files
COPY . .
# Generate autoloader
RUN composer dump-autoload --optimize
# Set permissions
RUN chown -R www-data:www-data /var/www \
   && chmod -R 755 /var/www/storage
CMD ["php-fpm"]