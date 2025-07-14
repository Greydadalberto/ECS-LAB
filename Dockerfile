FROM php:8.2-apache

# Update system packages to reduce vulnerabilities
RUN apt-get update && apt-get upgrade -y && apt-get clean

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

COPY index.php /var/www/html/

EXPOSE 80
