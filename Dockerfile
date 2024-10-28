# Use the official PHP Apache image
FROM php:7.4-apache

# Install necessary packages
RUN apt-get update && apt-get install -y \
    iputils-ping \
    && docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY app/ /var/www/html/
COPY app/images/ /var/www/html/images/

# Create the uploads directory
RUN mkdir -p /var/www/html/uploads

# Ensure ownership is assigned to www-data
RUN chown -R www-data:www-data /var/www/html/uploads 

# Optional: Set correct permissions
RUN chmod -R 777 /var/www/html/uploads 

# Expose port 80
EXPOSE 80

# Run Apache in the foreground
CMD ["apache2-foreground"]
