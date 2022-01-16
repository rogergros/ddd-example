FROM php:8.1-fpm

# Install OS packages
RUN apt-get update \
    && apt-get install -y \
        unzip \
        imagemagick \
    && apt-get clean all \
;

# Install PHP Extension installer (https://github.com/mlocati/docker-php-extension-installer)
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/install-php-extensions

# Install PHP extensions
RUN install-php-extensions \
        bcmath \
        intl \
        pdo_mysql \
        zip \
;

# Install composer
RUN install-php-extensions @composer-^2

# Prepare workdir
RUN rm -rf /var/www/*
WORKDIR /var/www

# Run PHP-fpm as root
CMD ["php-fpm", "-R"]
