FROM php:8.4-cli-alpine AS base

RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    sqlite-dev \
    postgresql-dev \
    icu-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql zip intl mbstring bcmath pcntl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM base AS vendor

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-interaction --prefer-dist

FROM base AS assets

RUN apk add --no-cache nodejs npm
COPY package.json package-lock.json* ./
RUN npm ci --ignore-scripts 2>/dev/null || npm install --ignore-scripts
COPY vite.config.js resources ./resources
COPY public ./public
RUN npm run build

FROM base AS runtime

COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=assets /var/www/html/public/build ./public/build
COPY . .

RUN composer dump-autoload --optimize \
    && php artisan config:clear \
    && chown -R www-data:www-data storage bootstrap/cache

ENV PORT=8080
EXPOSE 8080

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
