FROM php:7.4-fpm-alpine

RUN apk add --no-cache openssl bash postgresql-dev
RUN docker-php-ext-install bcmath pdo pdo_pgsql

WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php

RUN mkdir /var/www/storage
RUN chmod -R 777 /var/www/storage

EXPOSE 9000

ENTRYPOINT [ "php-fpm" ]
