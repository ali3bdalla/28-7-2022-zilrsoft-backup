

# Set the base image
FROM php:7.3

# Update packages
RUN apt-get -yqq update

# Install php extensions
RUN apt-get install -yqq libpq-dev libcurl4-gnutls-dev libicu-dev zlib1g-dev libpng-dev libxml2-dev libzip-dev libbz2-dev
RUN docker-php-ext-install pdo_pgsql curl json intl gd xml zip bz2 opcache

# Install script dependencies
RUN apt-get install -yqq gnupg

# Upgrade to Node 12
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -

# Install Node
RUN apt-get install -yqq nodejs

# Install Composer
RUN apt-get install -yqq curl
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install & enable Xdebug for code coverage reports
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

# Install client to import db structure
RUN apt-get install -yqq postgresql-client

# Install for deployment
RUN apt-get install -yqq openssh-client

# Install Laravel Envoy
RUN composer global require "laravel/envoy"

# Clear out the local repository of retrieved package files
RUN apt-get clean
