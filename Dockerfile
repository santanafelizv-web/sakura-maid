FROM php:8.4-cli

WORKDIR /app

RUN apt-get update \
    && apt-get install -y default-mysql-client default-libmysqlclient-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY . /app

EXPOSE 8080

ENTRYPOINT ["sh", "-lc", "php -S 0.0.0.0:$PORT -t public public/router.php"]
