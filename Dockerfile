FROM php:7.4-apache

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

COPY index.php /var/www/html/

EXPOSE 80
