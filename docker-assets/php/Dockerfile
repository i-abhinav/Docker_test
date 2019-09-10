FROM php:7.3-fpm-alpine
RUN apk update; \
    apk upgrade;
RUN docker-php-ext-install iconv pdo pdo_mysql mysqli

#Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=. --filename=composer
RUN mv composer /usr/local/bin/



# Bundle app source 
# COPY src/ /var/www/html/

# Install app dependencies
# RUN cd /src && \
#     composer install --no-interaction 

# EXPOSE 80