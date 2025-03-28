FROM php:8.2-fpm

ARG UID
ARG GID

RUN groupmod -g $GID www-data \
    && usermod -u $UID -g $GID www-data

WORKDIR /var/www/html/

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        zip \
        unzip \
        libzip-dev \
        default-mysql-client \
        cron \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql bcmath

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN chmod -R 775 . \
    && chown -R www-data:www-data .

RUN mkdir -p /var/www/.composer/cache/files \
    && chown -R www-data:www-data /var/www/.composer/cache
    
USER www-data