FROM php:7.3-fpm

ARG user=ali
ARG uid=533
WORKDIR /var/www/html/
ARG DEBIAN_FRONTEND=noninteractive


# Essentials

# RUN echo "UTC" > /etc/timezone
# RUN apt-get update
# RUN apt-get install -y  zip unzip curl sqlite nginx supervisor

# Installing bash
# RUN apt-get install -y  bash
# RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

# # # Installing PHP\
# RUN apt-get install -y software-properties-common 
# RUN add-apt-repository ppa:ondrej/php
# RUN apt-get update
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# RUN docker-php_ext-install pdo_pgsql mbstring 

# RUN docker-php-ext-install  php_common \
#     php_pdo \
#     php_opcache \
#     php_zip \
#     php_phar \
#     php_iconv \
#     php_cli \
#     php_curl \
#     # php7.3-openssl \
#     php_mbstring \
#     php_tokenizer \
#     php_fileinfo \
#     php_json \
#     php_xml \
#     php_xmlwriter \
#     php_simplexml \
#     php_dom \
#     # php7.3-pgsql \
#     # php7.3-pdo_sqlite \
#     php_tokenizer 

# RUN apt-get install php7.3-common  php7.3-xml php7.3-xmlrpc php7.3-curl php7.3-gd php7.3-imagick php7.3-cli php7.3-dev php7.3-imap php7.3-mbstring php7.3-opcache php7.3-soap php7.3-zip php7.3-intl -y
# Installing composer
# RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
# RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
# RUN rm -rf composer-setup.php

# Configure supervisor
# RUN 

# Configure php_fpm
RUN mkdir -p /run/php/
RUN touch /run/php/php7.3-fpm.pid
RUN touch /run/php/php7.3-fpm.sock

COPY ./docker/php-fpm.conf /etc/php7.3/php-fpm.conf

# # Configure nginx
# # RUN echo "daemon off;" >> /etc/nginx/nginx.conf
# COPY ./docker/nginx/default.conf /etc/nginx/sites-enabled/default
# COPY ./docker/nginx/fastcgi-php.conf /etc/nginx/fastcgi-php.conf

# RUN mkdir -p /run/nginx/
# RUN touch /run/nginx/nginx.pid

# RUN ln -sf /dev/stdout /var/log/nginx/access.log
# RUN ln -sf /dev/stderr /var/log/nginx/error.log

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user
