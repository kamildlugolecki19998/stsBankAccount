FROM php:8.1-apache

#enable apache mod_rewrite

RUN a2enmod rewrite

COPY /apache/apache.conf /etc/apache2/sites-enabled/000-default.conf

RUN apt-get update && apt-get install -y \
git \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    htop \
    mc \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
                    intl \ 
                    pdo_mysql \
                    xml \
                    zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN COMPOSER_ALLOW_SUPERUSER=1 

COPY ./app /var/www/html

WORKDIR /var/www/html

RUN composer install

EXPOSE 80

CMD ["apache2-foreground"]