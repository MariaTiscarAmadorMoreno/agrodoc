FROM php:8.1-apache

# Instalar extensiones necesarias para PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copiar el código al directorio de Apache
COPY ./public /var/www/html
COPY ./src /var/www/src

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html /var/www/src

# Exponer el puerto 80
EXPOSE 80
# Habilitar el modulo rewrite
RUN a2enmod rewrite

