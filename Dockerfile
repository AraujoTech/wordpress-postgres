FROM php:8.2-apache

# Install extra PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    postgresql \
    wget \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN a2enmod rewrite && service apache2 restart
COPY . /var/www/html/   
