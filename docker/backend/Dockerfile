FROM php:8.3.12-fpm

RUN apt-get update; \
    apt-get install -y --no-install-recommends unzip;

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql intl pcntl

RUN ln -sf /usr/share/zoneinfo/UTC /etc/localtime

ARG USER_ID
ARG GROUP_ID
RUN groupadd --gid "$GROUP_ID" dev \
    && useradd --uid "$USER_ID" --gid dev --shell /bin/bash --create-home dev

COPY --from=composer:2.8.1 /usr/bin/composer /usr/bin/composer
RUN su dev -c 'mkdir -p /home/dev/.composer/ /home/dev/app/'

COPY www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /app/
