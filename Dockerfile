FROM php:8.2-apache

# Install extra PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    postgresql \
    unzip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && rm -rf /var/lib/apt/lists/*

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \ 
    && chmod +x wp-cli.phar \ 
    && mv wp-cli.phar /usr/local/bin/wp


RUN a2enmod rewrite && service apache2 restart 


COPY ./wordpress/ /var/www/html/

COPY ./composer.json    /var/www/html/

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN composer config --no-plugins allow-plugins.composer/installers true && composer install && composer fund

 