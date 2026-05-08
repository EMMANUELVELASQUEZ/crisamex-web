FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libwebp-dev libzip-dev \
    zip unzip curl \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql mysqli gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2dismod mpm_event || true \
    && a2dismod mpm_worker || true \
    && a2dismod mpm_prefork || true \
    && a2enmod mpm_prefork \
    && a2enmod rewrite headers \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

RUN mkdir -p public/uploads logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && echo "upload_max_filesize = 10M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/php.ini \
    && echo "display_errors = Off" >> /usr/local/etc/php/php.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/php.ini

EXPOSE 80
CMD ["apache2-foreground"]
