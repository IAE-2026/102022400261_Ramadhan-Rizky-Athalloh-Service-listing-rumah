FROM php:8.2-cli

RUN apt-get update -o Acquire::Retries=5 && \
    apt-get install -y -o Acquire::Retries=5 --no-install-recommends \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]