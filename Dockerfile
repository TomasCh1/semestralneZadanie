# -------------------------------------
# 1) Vite build fáza (Node)
# -------------------------------------
FROM node:18-alpine AS node-builder

WORKDIR /var/www

# 1.1) Skopíruj package manifesty a vite config
COPY package*.json vite.config.js ./

# 1.2) Nainštaluj JS závislosti
RUN npm ci

# 1.3) Skopíruj frontend zdroje a vygeneruj build
COPY resources resources
RUN npm run build


# -------------------------------------
# 2) PHP-FPM fáza (Alpine)
# -------------------------------------
FROM php:8.4-fpm-alpine

# 2.1) Systémové nástroje a knižnice pre pdo_mysql, mbstring, zip, oniguruma
RUN apk add --no-cache \
      zip unzip libzip-dev oniguruma-dev \
    && docker-php-ext-install \
      pdo_mysql mbstring zip \
    && rm -rf /var/cache/apk/*

# 2.2) Nainštaluj Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 2.3) Skopíruj celý Laravel projekt
COPY . .

# 2.4) Skopíruj .env (pre lokálny dev; v produkcii radšej mountovať)
COPY .env.example .env

# 2.5) Vygeneruj APP_KEY a cache konfiguráciu
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
 && php artisan key:generate --ansi \
 && php artisan config:cache

# 2.6) Pridaj vygenerovaný frontend build
COPY --from=node-builder /var/www/public/build public/build

# 2.7) Nastav povolenia
RUN chmod +w bootstrap/cache \
 && chmod +w storage \
 && chown -R www-data:www-data storage bootstrap/cache

# 2.8) Expose pre PHP-FPM (interný port)
EXPOSE 9000

# 2.9) Jednoduchý healthcheck (voliteľné)
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s \
  CMD wget --quiet --spider http://127.0.0.1:9000 || exit 1

# 2.10) Štart PHP-FPM
CMD ["php-fpm"]

RUN composer require torann/geoip
