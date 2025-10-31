# Use official PHP image with Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Copy project files to web root
COPY . /var/www/html/

# Expose port 80 (Render automatically maps it)
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
