FROM php:8.2-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Set public/ as document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache config to use new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install PDO
RUN docker-php-ext-install pdo pdo_mysql
