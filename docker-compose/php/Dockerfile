FROM php:7.4-fpm AS crmeb_php
WORKDIR "/var/www"

RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        libonig-dev \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        mysqli \
        pdo_mysql \
        zip \
        pcntl \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "open_basedir = /tmp:/var" > /usr/local/etc/php/conf.d/open_basedir.ini

COPY docker-compose/php/zz-opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini
COPY docker-compose/php/zz-www.conf /usr/local/etc/php-fpm.d/zz-www.conf

COPY --chown=www-data:www-data crmeb/ /var/www/

RUN mkdir -p /var/www/runtime /var/www/public/uploads \
    && chown -R www-data:www-data /var/www/runtime /var/www/public/uploads

# Declare /var/www as a volume so nginx (via volumes_from) can access the baked-in code.
# Docker auto-populates this anonymous volume from image content at container creation.
VOLUME /var/www
