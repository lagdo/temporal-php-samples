ARG FRANKENPHP_VERSION=1.2.5
ARG PHP_VERSION=8.3
ARG PROTOBUF_VERSION=4.29.1

FROM dunglas/frankenphp:${FRANKENPHP_VERSION}-php${PHP_VERSION}-alpine

ARG PHP_USER
ARG PHP_UID
ARG PHP_GID
ENV PHP_USER=${PHP_USER}
ENV PHP_UID=${PHP_UID}
ENV PHP_GID=${PHP_GID}

# Be sure to replace "your-domain-name.example.com" by your domain name
# ENV SERVER_NAME=your-domain-name.example.com
# If you want to disable HTTPS, use this value instead:
ENV SERVER_NAME=:80

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN set -xe && apk add --no-cache --virtual .build-deps bash curl wget zip vim git \
  libzip-dev freetype-dev icu-dev libmcrypt-dev libxslt-dev pcre patch openssh-client

RUN --mount=type=bind,from=mlocati/php-extension-installer:2,source=/usr/bin/install-php-extensions,target=/usr/local/bin/install-php-extensions \
  install-php-extensions opcache zip intl sockets bcmath ctype phar \
  curl dom fileinfo gd mbstring openssl redis tokenizer \
  # Database
  pdo pdo_sqlite sqlite3 pdo_mysql mysqlnd mysqli pdo_pgsql pgsql \
  # PHP PECL extensions
  xdebug

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

RUN addgroup -g ${PHP_GID} --system ${PHP_USER}
RUN adduser -G ${PHP_USER} --system -D -s /bin/sh -u ${PHP_UID} ${PHP_USER}

# Create project root
RUN mkdir -p /app

# Make sure files/folders needed by the processes are accessible
RUN chown -R ${PHP_USER}:${PHP_USER} /app /data/caddy /config/caddy

# Add additional capability to bind to port 80 and 443
RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

# Switch to use a non-root user from here on
USER ${PHP_USER}

WORKDIR /app
