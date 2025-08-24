FROM php:8.2-fpm

RUN apt-get update && apt-get upgrade -y \
    && apt-get install apt-utils -y \
      && apt-get install procps -y \
    && apt-get install -y supervisor -y \
    && apt-get install git zip vim libzip-dev libgmp-dev libffi-dev libssl-dev libpng-dev libcurl4-openssl-dev libicu-dev libpq-dev wget jq curl -y \
    && docker-php-ext-install -j$(nproc) gd sockets zip curl intl gmp pcntl bcmath ffi pdo pdo_mysql pdo_pgsql \
    && pecl install redis-stable \
    && docker-php-ext-enable redis \
    && docker-php-source delete \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y

COPY ./docker/php/install-composer.sh /
RUN sh /install-composer.sh

COPY ./docker/php/php.ini /usr/local/etc/php/

RUN usermod -u 998 www-data

# Create supervisor directories and set permissions
RUN mkdir -p /var/log/supervisor /var/run/supervisor \
    && chown -R www-data:www-data /var/log/supervisor /var/run/supervisor

COPY . /googlesheet
RUN chown -R www-data:www-data /googlesheet
WORKDIR /googlesheet

# Create startup script and set proper permissions
COPY ./docker/php/start.sh /start.sh
RUN chmod +x /start.sh && chown www-data:www-data /start.sh

USER www-data
EXPOSE 7778

CMD ["/start.sh"]
