FROM php:7.4-fpm

RUN apt-get update && \
    apt-get install -y git zip unzip && \
    rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /app

COPY composer.json /app

RUN composer install