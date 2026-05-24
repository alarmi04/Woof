# Stage 1: Compilar assets del frontend con Node.js
FROM node:20 as frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Servidor Apache y PHP para Laravel
FROM php:8.2-apache

# Instalar dependencias del sistema y Python 3
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    python3 \
    python3-pip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Crear un enlace simbólico para que el comando 'python' ejecute Python 3
RUN ln -s /usr/bin/python3 /usr/bin/python

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Habilitar el módulo rewrite de Apache (indispensable para las rutas de Laravel)
RUN a2enmod rewrite

# Configurar el directorio raíz de Apache para que apunte a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el código del proyecto compilado desde la etapa anterior
COPY --from=frontend-builder /app /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Asignar los permisos correctos a las carpetas de almacenamiento y caché de Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Indicar el puerto en el que escucha Apache
EXPOSE 80

# Ejecutar las migraciones y arrancar el servidor Apache
CMD php artisan migrate --force && apache2-foreground
