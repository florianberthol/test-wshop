FROM php:8.4-fpm

RUN apt-get update && \
     apt-get install -y vim locales coreutils apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev npm;

# Locale fr et en
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# Installer les extensions PHP requises pour Symfony
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql mysqli opcache intl zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . /var/www

RUN useradd -m -u 1000 symfony && \
    chown -R symfony:symfony /var/www
USER symfony

EXPOSE 9000
CMD ["php-fpm"]