FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.2 \
    php8.2-mysql \
    php8.2-gd \
    php8.2-zip \
    php8.2-curl \
    php8.2-mbstring \
    libapache2-mod-php8.2 \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite headers php8.2

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN cat > /etc/apache2/sites-available/000-default.conf << 'APACHEEOF'
<VirtualHost *:80>
    ServerAdmin contacto@crisamex.com
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
APACHEEOF

WORKDIR /var/www/html
COPY . .

RUN mkdir -p public/uploads logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/public/uploads

EXPOSE 80
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

# Mostrar errores PHP para debug
RUN echo "display_errors = On" >> /etc/php/8.2/apache2/php.ini \
    && echo "error_reporting = E_ALL" >> /etc/php/8.2/apache2/php.ini \
    && echo "log_errors = On" >> /etc/php/8.2/apache2/php.ini

RUN a2enmod rewrite
