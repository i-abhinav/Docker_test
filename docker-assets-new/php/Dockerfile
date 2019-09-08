FROM php:7.3-fpm-alpine
RUN apk update; \
    apk upgrade;
RUN docker-php-ext-install pdo pdo_mysql mysqli

#Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=. --filename=composer
RUN mv composer /usr/local/bin/
# COPY src/ /var/www/html/
# EXPOSE 80