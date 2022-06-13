FROM php:8-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y zip git

RUN docker-php-ext-install pdo_mysql

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY . .

RUN composer install
