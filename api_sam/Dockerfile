# Use a imagem oficial do PHP com extensões necessárias para o Laravel
FROM php:8.2-fpm

# Configurar fuso horário e corrigir problema de data nos repositórios
RUN apt-get update || true && apt-get install -y tzdata
RUN ln -fs /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime && dpkg-reconfigure -f noninteractive tzdata

# Instalar dependências necessárias
RUN apt-get update --allow-releaseinfo-change && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    netcat-openbsd \
    && docker-php-ext-install pdo pdo_pgsql

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

CMD php artisan serve --host=0.0.0.0 --port=8000 & php artisan serve --host=0.0.0.0 --port=8001

