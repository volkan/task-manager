FROM php:7.1-fpm
ARG TIMEZONE


RUN apt-get update
RUN apt-get install --no-install-recommends -y git-core unzip
RUN pecl install xdebug-2.5.0 && docker-php-ext-enable xdebug
RUN apt-get install --no-install-recommends -y libzip-dev zip
RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install zip

# Install Composer
COPY --from=composer:1.8 /usr/bin/composer /usr/local/bin/composer

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
&& "date"

RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
&& echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www/symfony
