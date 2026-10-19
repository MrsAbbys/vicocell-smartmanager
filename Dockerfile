FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apk update && apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    sqlite-dev \
    sqlite \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring xml zip gd bcmath

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos para storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto expuesto por Render
EXPOSE 8080

# Comando de arranque: enlazar almacenamiento, preparar BD y levantar servidor
CMD touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/database \
    && chmod -R 775 /var/www/html/database \
    && php artisan storage:link || true \
    && php artisan migrate --force || true \
    && php artisan db:seed --force || true \
    && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
