ARG ALPINE_VERSION=3.19
ARG PHP_VERSION=8.3
ARG PROTOBUF_VERSION=4.29.1

FROM alpine:${ALPINE_VERSION}
FROM php:${PHP_VERSION}-fpm-alpine

ARG PHP_USER
ARG PHP_UID
ARG PHP_GID
ENV PHP_USER=${PHP_USER}
ENV PHP_UID=${PHP_UID}
ENV PHP_GID=${PHP_GID}

RUN apk add --no-cache bash curl wget zip vim git nginx supervisor

RUN set -xe && apk add --no-cache --virtual .build-deps \
  libzip-dev freetype-dev icu-dev libmcrypt-dev libxslt-dev patch openssh-client

RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions opcache zip intl sockets bcmath ctype phar \
  curl dom fileinfo gd mbstring opcache openssl redis tokenizer \
  # Database
  pdo pdo_sqlite sqlite3 pdo_mysql mysqlnd mysqli pdo_pgsql pgsql mongodb \
  # PHP PECL extensions
  amqp xdebug

ARG PROTOBUF_VERSION
RUN apk add --no-cache autoconf grpc-cpp grpc-dev $PHPIZE_DEPS && \
  pecl install protobuf-${PROTOBUF_VERSION} && \
  GRPC_VERSION=$(apk info grpc -d | grep grpc | cut -d- -f2) && \
  git clone --depth 1 -b v${GRPC_VERSION} https://github.com/grpc/grpc /tmp/grpc && \
  cd /tmp/grpc/src/php/ext/grpc && \
  phpize && \
  ./configure && \
  make && \
  make install && \
  rm -rf /tmp/grpc && \
  apk del --no-cache autoconf grpc-dev $PHPIZE_DEPS && \
  echo "extension=grpc.so" > /usr/local/etc/php/conf.d/grpc.ini

# PHP Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configure PHP-FPM
COPY workflow-api/nginx-phpfpm/config/www.conf /etc/php/php-fpm.d/www.conf

# Configure Nginx - http
COPY workflow-api/nginx-phpfpm/config/nginx.conf /etc/nginx/nginx.conf

# Configure Nginx - default server
COPY workflow-api/nginx-phpfpm/config/conf.d /etc/nginx/conf.d/

# Configure Supervisord
COPY workflow-api/nginx-phpfpm/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN addgroup -g ${PHP_GID} --system ${PHP_USER}
RUN adduser -G ${PHP_USER} --system -D -s /bin/sh -u ${PHP_UID} ${PHP_USER}

# Create project root
RUN mkdir -p /var/www

# Make sure files/folders needed by the processes are accessible
RUN chown -R ${PHP_USER}:${PHP_USER} /var/www /run /var/log /var/lib/nginx /var/log/nginx

# Switch to use a non-root user from here on
USER ${PHP_USER}

WORKDIR /var/www
