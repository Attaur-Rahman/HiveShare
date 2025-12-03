FROM richarvey/nginx-php-fpm:3.1.6

# Go to the app directory used by the base image
WORKDIR /var/www/html

# Copy app code into the container
COPY . .

# Install PHP dependencies (creates /var/www/html/vendor)
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Image config
ENV SKIP_COMPOSER 1                # now OK, composer already ran
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]
