# Use the PHP 8.2 Apache base image
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    libpq-dev \
    libgd-dev \
    git \
    && docker-php-ext-install pdo_pgsql zip gd

# Set the Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV VIRTUAL_HOST_CONFIG=/etc/apache2/sites-available/api.curricompass.online.conf

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

# Ensure the Composer binary is available
ENV PATH="${PATH}:/usr/local/bin"

# Install PHP dependencies using Composer
RUN composer install --no-scripts --no-autoloader

# Install specific PHP packages
RUN composer require react/react phpoffice/phpspreadsheet maatwebsite/excel

# Ensure proper permissions for
