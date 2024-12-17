ARG ALPINE_VERSION=3.19
ARG PHP_VERSION=8.3
ARG UNIT_VERSION=1.33.0
ARG PROTOBUF_VERSION=4.29.1

# Nginx Unit downloader
FROM alpine:${ALPINE_VERSION} AS nginx-unit-downloader

ARG UNIT_VERSION
WORKDIR "/tmp/unit"
ADD ["https://codeload.github.com/nginx/unit/tar.gz/refs/tags/${UNIT_VERSION}", "/tmp/unit.tar.gz"]
RUN tar zxvf /tmp/unit.tar.gz --strip=1 -C "/tmp/unit"

# Nginx Unit builder
ARG PHP_VERSION
ARG ALPINE_VERSION
FROM php:${PHP_VERSION}-zts-alpine${ALPINE_VERSION} AS nginx-unit-builder

RUN set -eux \
  && apk add --update --no-cache alpine-sdk curl openssl-dev pcre-dev

COPY --from=nginx-unit-downloader ["/tmp/unit", "/build/unit/"]
ENV DESTDIR /opt/unit/
WORKDIR "/build/unit/"
ARG PHP_VERSION

RUN set -eux \
  && ./configure --log=/var/log/unitd.log \
  && ./configure php --module="php" \
  && make -j "$(nproc)" \
  && make -j "$(nproc)" install \
  && make clean

# Root builder
FROM alpine:${ALPINE_VERSION}
FROM php:${PHP_VERSION}-zts-alpine

ARG PHP_USER
ARG PHP_UID
ARG PHP_GID
ENV PHP_USER=${PHP_USER}
ENV PHP_UID=${PHP_UID}
ENV PHP_GID=${PHP_GID}

RUN set -xe && apk add --no-cache --virtual .build-deps bash curl wget zip vim git \
  libzip-dev freetype-dev icu-dev libmcrypt-dev libxslt-dev pcre patch openssh-client

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

# Nginx Unit
COPY --from=nginx-unit-builder ["/opt/unit/", "/opt/unit/"]
RUN cp -R /opt/unit/usr/local/* /usr/local

# PHP Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Configure Nginx Unit
COPY workflow-api/nginx-unit/config/www.json /docker-entrypoint.d/config.json
COPY workflow-api/nginx-unit/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

RUN addgroup -g ${PHP_GID} --system ${PHP_USER}
RUN adduser -G ${PHP_USER} --system -D -s /bin/sh -u ${PHP_UID} ${PHP_USER}

# Create project root
RUN mkdir -p /var/www

# Make sure files/folders needed by the processes are accessible
RUN chown -R ${PHP_USER}:${PHP_USER} /var/www

WORKDIR /var/www
