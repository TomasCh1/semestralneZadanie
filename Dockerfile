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

# 2.1) Systémové nástroje a knižnice pre mbstring, zip, oniguruma
RUN apk add --no-cache \
      zip unzip libzip-dev oniguruma-dev \
    && docker-php-ext-install \
      pdo_mysql mbstring zip \
    && rm -rf /var/cache/apk/*

# 2.2) Nainštaluj Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# 2.3) Skopíruj celý Laravel projekt (vrátane artisan)
COPY . .

# 2.4) Nainštaluj PHP závislosti
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 2.5) Pridaj vygenerovaný frontend build
COPY --from=node-builder /var/www/public/build public/build

RUN chmod +w bootstrap/cache \
 && chmod +w storage

# 2.6) Nastav práva na storage a cache
RUN chown -R www-data:www-data storage bootstrap/cache

# 2.7) Štart PHP-FPM
CMD ["php-fpm"]
