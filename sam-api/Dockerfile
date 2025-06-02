# Use a imagem oficial do PHP com extensões necessárias para o Laravel
FROM php:8.2-fpm

# Instalar dependências do sistema e da GD
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar o código do Laravel
COPY . .

# Instalar dependências do Laravel
RUN composer install --optimize-autoloader --no-dev

# Definir permissões para a pasta de cache do Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expor a porta padrão do PHP-FPM
EXPOSE 9000

# Comando inicial
CMD ["php-fpm"]