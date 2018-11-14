FROM php:7.2-fpm
#Install required components for mysql connectivity
RUN apt-get update && apt-get install -y \
		apt-utils \
		libfreetype6-dev \
		libxml2-dev \
        libcurl4-gnutls-dev \
		git \
		zip \
	&& docker-php-ext-install -j$(nproc) mysqli pdo_mysql bcmath zip exif

#Install composer and testing frameworks
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN composer global require phpunit/phpunit \
    && composer global require phpunit/dbunit \
    && composer global require sebastian/phpcpd \
    && composer global require squizlabs/php_codesniffer
RUN export PATH=~/.composer/vendor/bin:$PATH

#Reset the www-data permissions so it can read the files on the server.
RUN sed -ri 's/^www-data:x:33:33:/www-data:x:1000:50:/' /etc/passwd
WORKDIR /app