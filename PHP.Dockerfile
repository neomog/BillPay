FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

# If you need to install mysqli instead of PDO
# RUN docker-php-ext-install mysqli

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    vim \
    unzip

#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#WORKDIR /var/www

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html