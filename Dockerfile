FROM php:8.1.5-fpm-alpine3.15

# Installer les dépendances nécessaires pour PHP et les outils communs
RUN apk update \
    && apk add --no-cache $PHPIZE_DEPS \
       openssl-dev bash git nano \
       icu-dev libzip-dev libxml2-dev oniguruma-dev \
       libpng-dev libxslt-dev

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer

# Installer Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | sh \
    && apk add symfony-cli

# Installer les extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql opcache intl zip calendar dom mbstring gd xsl \
    && pecl install apcu && docker-php-ext-enable apcu

# Installer Node.js et npm
RUN apk add --no-cache nodejs npm

# Configurer le répertoire de travail
WORKDIR /var/www

EXPOSE 9000

# Configurer le processus principal pour exécuter nginx et PHP-FPM
CMD ["php-fpm"]


