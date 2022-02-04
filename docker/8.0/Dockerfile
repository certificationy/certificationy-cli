FROM php:8.0-fpm-alpine

RUN apk add --no-cache --virtual .persistent-deps \
        bash \
		git \
		icu-libs \
 		zlib \
 		wget \
		ca-certificates \
		curl \
		libzip-dev

RUN set -xe \
	&& apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		zlib-dev \
	&& docker-php-ext-install \
		intl \
		zip \
	&& apk del .build-deps

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app

COPY php.ini /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer clear-cache

COPY start.sh /usr/local/bin/docker-app-start

RUN chmod +x /usr/local/bin/docker-app-start

