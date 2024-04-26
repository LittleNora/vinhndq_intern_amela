# Set master image
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install Additional dependencies
RUN apk update && apk add \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Add and Enable PHP-PDO Extenstions for PHP connect Mysql
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# This extension required for Laravel Horizon
RUN docker-php-ext-install pcntl

# Remove Cache
RUN rm -rf /var/cache/apk/*

COPY .docker/supervisord.conf /etc/supervisord.conf
COPY .docker/supervisor.d /etc/supervisor.d

# Use the default production configuration for PHP-FPM ($PHP_INI_DIR variable already set by the default image)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Add UID '1000' to www-data
#RUN usermod -u 1000 www-data
RUN #getent group www-data || addgroup -g 1000 www-data && adduser -D -u 1000 -G www-data www-data

# Copy existing application directory
COPY . .

# Chang app directory permission
RUN chown -R www-data:www-data .

# ENV ENABLE_CRONTAB 1
# ENV ENABLE_HORIZON 1
ENV ENABLE_WORKER 1

ENTRYPOINT ["sh", "/var/www/html/.docker/docker-entrypoint.sh"]

CMD supervisord -n -c /etc/supervisord.conf
