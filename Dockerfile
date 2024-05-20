FROM php:apache

RUN apt-get update

RUN a2enmod rewrite
RUN docker-php-ext-install opcache
RUN apt-get install git -y

# copy source files
COPY . /var/www/html

# composer install
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
COPY ./composer.json /var/www/composer.json
WORKDIR /var/www/
RUN composer install

WORKDIR /var/www/html