# Use the PHP 8.2 Apache base image
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    libpq-dev \
    git \
    && docker-php-ext-install pdo_pgsql zip

# Set the Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Update Apache configuration files to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application code to the container
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies using Composer
RUN composer install

# Ensure proper permissions for storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Install ReactPHP and any other PHP dependencies
RUN composer require react/react

# Expose port 80 for Apache
EXPOSE 80


# Start Apache in the foreground
CMD ["apache2-foreground"]
