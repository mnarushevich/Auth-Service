FROM dunglas/frankenphp:1-php8.3.3-alpine

RUN install-php-extensions \
    pcntl \
    intl \
    pdo_mysql zip mysqli sockets redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install nodejs npm
RUN apk add --update nodejs npm

WORKDIR /var/www

COPY ./  ./
RUN composer install
RUN npm install

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
