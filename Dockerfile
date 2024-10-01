FROM php:7.4-apache

RUN apt-get update && apt-get install -y iputils-ping

RUN docker-php-ext-install mysqli
RUN a2enmod rewrite

COPY app/images/ /var/www/html/images/

# Create uploads and images directories with appropriate permissions
RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads
RUN chmod -R 777 /var/www/html/images

COPY app/ /var/www/html/

EXPOSE 80
