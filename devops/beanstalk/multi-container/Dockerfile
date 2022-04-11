FROM php:8.0-cli

EXPOSE 80/tcp
WORKDIR /app
COPY index.php /app
CMD ["php", "-S", "0.0.0.0:80", "index.php"]