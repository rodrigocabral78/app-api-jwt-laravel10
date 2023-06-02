FROM php:8.1.18-alpine

WORKDIR /var/www/html

COPY ./php.ini /etc/php/8.1/cli/conf.d/99-docker.ini
COPY ../../../../. /var/www/html

RUN curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer ; \
  composer update ; composer dump-autoload ; \
  artisan cache:clear -vvv && \
  artisan config:clear -vvv && \
  artisan route:clear -vvv && \
  artisan view:clear -vvv && \
  artisan event:clear -vvv && \
  artisan optimize:clear -vvv && \
  artisan clear-compiled -vvv && \
  artisan optimize -vvv

EXPOSE 8000

CMD php -welsS 0.0.0.0:8000 -t public public/index.php
