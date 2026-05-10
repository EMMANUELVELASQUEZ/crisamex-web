FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    php8.1-mysql \
    php8.1-gd \
    php8.1-zip \
    php8.1-curl \
    php8.1-mbstring \
    libapache2-mod-php8.1 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite headers php8.1

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

RUN mkdir -p public/uploads logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]
