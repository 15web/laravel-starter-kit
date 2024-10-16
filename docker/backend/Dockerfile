FROM php:8.3.12-fpm AS base

RUN apt-get update; \
    apt-get install -y --no-install-recommends unzip

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql intl

COPY --from=composer:2.7.9 /usr/bin/composer /usr/bin/composer

RUN ln -sf /usr/share/zoneinfo/UTC /etc/localtime

COPY www.conf /usr/local/etc/php-fpm.d/www.conf

ARG USER_ID
RUN groupadd --gid "$USER_ID" dev \
    && useradd --uid "$USER_ID" --gid dev --shell /bin/bash --create-home dev

RUN su dev -c 'mkdir -p /home/dev/.composer/ /home/dev/app/'

USER dev

WORKDIR /app/

FROM base AS queue

USER root

# https://symfony.com/doc/current/messenger.html#graceful-shutdown
RUN install-php-extensions pcntl

RUN apt-get update; \
    apt-get install -y --no-install-recommends supervisor; \
    rm -rf /var/lib/apt/lists/*

COPY supervisord/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY supervisord/supervisord.conf /etc/supervisor/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
