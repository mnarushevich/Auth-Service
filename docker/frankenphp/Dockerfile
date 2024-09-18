FROM dunglas/frankenphp:1-php8.3.3-alpine

RUN install-php-extensions \
    pcntl \
    pdo_mysql zip mysqli sockets redis

# Install Task
RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b "/usr/local/bin"

WORKDIR /app

COPY ./src /app

ENTRYPOINT ["php", "artisan", "octane:frankenphp"]
