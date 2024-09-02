ARG PHP_VERSION=8.3.8
ARG COMPOSER_VERSION=2.7.7
FROM composer:${COMPOSER_VERSION} AS composer

FROM php:${PHP_VERSION}-apache-bookworm AS base

ENV WORKDIR=/var/www/project-cgrd

WORKDIR ${WORKDIR}

ARG gid=2000
ARG uid=2000

RUN addgroup --gid ${gid} --system app \
    && adduser --group app --system --disabled-password -u ${uid}

RUN apt-get update && apt-get install --no-install-recommends  \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql

COPY ./.docker/conf/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY ./.docker/conf/ports.conf /etc/apache2/ports.conf

FROM base AS builder

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN apt-get update && apt-get install --no-install-recommends git -y && apt-get clean &&  rm -rf /var/lib/apt/lists/*

COPY composer.* ${WORKDIR}

RUN composer install --prefer-dist --no-interaction --no-progress --no-scripts --optimize-autoloader

FROM base AS production

COPY . ${WORKDIR}

COPY --from=builder ${WORKDIR}/vendor ${WORKDIR}/vendor

RUN chown -R app:app /var/www/ && \
    chown -R app:app /etc/apache2 && \
    a2enmod rewrite

USER app