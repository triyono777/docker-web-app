FROM php:8.4-cli-alpine

WORKDIR /var/www/html

RUN apk add --no-cache $PHPIZE_DEPS bash git unzip \
    && docker-php-ext-install pdo_mysql \
    && apk del $PHPIZE_DEPS

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

COPY . .

RUN composer dump-autoload --optimize \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod +x docker/entrypoint.sh \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["docker/entrypoint.sh"]
