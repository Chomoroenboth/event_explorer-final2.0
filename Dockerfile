FROM laravelsail/php82-composer:latest

RUN docker-php-ext-install pdo pdo_mysql
