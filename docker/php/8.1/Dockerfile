FROM php:8.1.18-alpine

WORKDIR /var/www

RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

EXPOSE 8000

CMD php -welsS 0.0.0.0:8000 -t public public/index.php
