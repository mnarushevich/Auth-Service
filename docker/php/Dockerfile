FROM php:8.3.3-fpm

RUN apt-get update && apt-get install --assume-yes apt-utils
RUN apt-get install -y \
            curl \
            wget \
            git \
            libzip-dev \
            expect \
            nano \
            vim \
            zip
#RUN docker-php-ext-configure zip --with-libzip
RUN printf "\n" | pecl install redis
RUN docker-php-ext-install -j$(nproc) pdo_mysql zip mysqli sockets
RUN docker-php-ext-enable redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Task
RUN sh -c "$(curl --location https://taskfile.dev/install.sh)" -- -d -b "/usr/local/bin"

# Set working directory PHP
WORKDIR /var/www


# copy files to image
COPY . /var/www

# copy nginx config to image
COPY ./docker/nginx/hosts/. /etc/nginx/conf.d

RUN cd ./src && composer install --verbose

CMD ["php-fpm"]
