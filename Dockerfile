FROM php:8.1-apache

COPY . /var/www/html/

RUN chmod -R 755 /var/www/html \
    && chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite

EXPOSE 80
