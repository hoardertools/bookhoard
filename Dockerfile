FROM php:8.4-fpm
LABEL Maintainer="Martijn Katerbarg <https://github.com/hoardertools/bookhoard>"
LABEL Description="Full Stack Container with Bookhoard, Nginx, PHP8.4 - Needs PostgreSQL to function, please read README. Complete docker-compose setup available."

ARG DEBIAN_FRONTEND=noninteractive

# Default Env
ENV \
  APP_NAME="Bookhoard" \
  APP_ENV="local" \
  APP_DEBUG="true" \
  LOG_CHANNEL="stack" \
  LOG_DEPRECATIONS_CHANNEL="null" \
  LOG_LEVEL="debug" \
  BROADCAST_DRIVER="log" \
  CACHE_DRIVER="file" \
  FILESYSTEM_DISK="local" \
  QUEUE_CONNECTION="sync" \
  SESSION_DRIVER="file" \
  SESSION_LIFETIME="120" \
  UPLOAD_LIMIT="2048M" \
  PHP_UPLOAD_LIMIT="2048M" \
  MEMORY_LIMIT="2048M" \
  PHP_MEMORY_LIMIT="2048M"

# Add in code
ADD . /bookhoard

# Install packages and remove default server definition
RUN apt-get -qq update \
    && apt-get -y upgrade\
    && apt-get install -y \
    nginx \
    curl \
    exiftool \
    cron \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    git \
    zip \
    p7zip \
    unzip \
    libicu-dev\
    libxslt-dev \
    supervisor

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pgsql
RUN docker-php-ext-configure pdo_pgsql && docker-php-ext-install pdo_pgsql
RUN docker-php-ext-configure bcmath && docker-php-ext-install bcmath
RUN docker-php-ext-configure opcache && docker-php-ext-install opcache
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-configure xsl && docker-php-ext-install xsl
RUN docker-php-ext-configure zip && docker-php-ext-install zip
RUN docker-php-ext-configure imagick && docker-php-ext-install imagick
RUN docker-php-ext-configure fileinfo && docker-php-ext-install fileinfo

RUN sed -i \
        -e "s/;listen.mode = 0660/listen.mode = 0666/g" \
        -e "s/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g" \
        /usr/local/etc/php-fpm.d/www.conf

RUN cd /bookhoard; php composer.phar install \
  # Cleanup
  && apt-get autoremove -y \
  && apt-get clean \
  && echo 'memory_limit = 4096M' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini \
  && echo 'upload_max_filesize = 2048M' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini \
  && echo 'post_max_size = 2048M' >> /usr/local/etc/php/conf.d/docker-php-ram-limit.ini \
  && rm -rf \
    /tmp/* \
    /var/lib/apt/lists/* \
    /var/tmp/

ADD ./docker/entrypoint.sh /entrypoint.sh
ADD ./docker/docker-conf/nginx.conf /etc/nginx/nginx.conf
ADD ./docker/docker-conf/supervisord.conf /etc/supervisord.conf

RUN git config --global --add safe.directory /bookhoard

RUN chmod +x /entrypoint.sh

EXPOSE 80
WORKDIR /bookhoard
CMD ["/entrypoint.sh"]
