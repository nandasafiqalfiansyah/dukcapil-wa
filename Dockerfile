# ========= 1) FRONTEND ASSETS =========
FROM node:20-alpine AS assets
WORKDIR /app

COPY package*.json ./
RUN --mount=type=cache,target=/root/.npm npm ci

COPY vite.config.* postcss.config.* tailwind.config.* ./
COPY resources ./resources
COPY public ./public

RUN npm run build


# ========= 2) COMPOSER VENDOR =========
FROM php:8.2-cli-alpine AS vendor
WORKDIR /app

# system deps + PHP extensions (WAJIB ADA GD)
RUN apk add --no-cache \
    git curl icu-dev libzip-dev oniguruma-dev \
    libpng-dev freetype-dev libjpeg-turbo-dev \
    libpq-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install gd intl zip pdo pdo_mysql pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install \
      --no-dev \
      --prefer-dist \
      --no-progress \
      --no-interaction \
      --no-scripts \
      --optimize-autoloader


# ========= 3) FINAL APP IMAGE =========
FROM php:8.2-cli-alpine AS app
WORKDIR /var/www/html

RUN apk add --no-cache \
    git bash curl tzdata shadow \
    icu-dev libzip-dev oniguruma-dev \
    libpng-dev freetype-dev libjpeg-turbo-dev \
    libpq-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
      gd intl zip bcmath pdo pdo_mysql pdo_pgsql

# copy source
COPY . .

# vendor & frontend build
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# permissions
RUN mkdir -p bootstrap/cache \
    storage/framework/{sessions,views,cache} storage/logs \
 && chown -R www-data:www-data storage bootstrap/cache

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]