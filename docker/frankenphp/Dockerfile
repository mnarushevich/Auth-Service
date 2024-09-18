FROM dunglas/frankenphp:1-php8.3.3-alpine

RUN install-php-extensions \
    pcntl \
    pdo_mysql zip mysqli sockets redis

# Install Task
RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b "/usr/local/bin"

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY ./src /app

RUN cd /app && composer install

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
