# Utiliza una imagen oficial de PHP
FROM php:8.2-cli

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    pkg-config \
    libbrotli-dev \
    libssl-dev \
    && docker-php-ext-install zip pcntl

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala Swoole a través de PECL
RUN pecl install swoole && docker-php-ext-enable swoole

# Copia los archivos del proyecto
WORKDIR /var/www
COPY . /var/www

# Instala dependencias de Laravel
RUN composer install --optimize-autoloader --no-dev

# Ejecuta dump-autoload para registrar comandos
RUN composer dump-autoload

# Publica los activos de Scribe
RUN php artisan vendor:publish --tag=scribe-assets --force

# Genera la documentación de Scribe
RUN php artisan scribe:generate

# Asegura permisos adecuados para public
RUN chmod -R 775 public vendor storage bootstrap/cache

# Expone el puerto 8000
EXPOSE 8000

# Comando para iniciar Laravel Octane
CMD ["php", "artisan", "octane:start", "--server=swoole", "--host=0.0.0.0"]
