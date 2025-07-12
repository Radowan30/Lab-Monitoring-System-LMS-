FROM node:20-bullseye as build-frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY resources/ resources/
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./
RUN npm run build

FROM php:8.2-fpm-bullseye as backend
WORKDIR /var/www/html
RUN apt-get update \
    && apt-get install -y libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=build-frontend /app/public/build /var/www/html/public/build
COPY --from=build-frontend /app/resources/ /var/www/html/resources/
COPY --from=build-frontend /app/package.json /var/www/html/package.json
COPY --from=build-frontend /app/vite.config.js /var/www/html/vite.config.js
COPY --from=build-frontend /app/tailwind.config.js /var/www/html/tailwind.config.js
COPY --from=build-frontend /app/postcss.config.js /var/www/html/postcss.config.js
COPY . .
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy built assets
COPY --from=build-frontend /app/public/build /var/www/html/public/build

# Set environment variables for Railway
ENV PORT=8080
EXPOSE 8080

# Entrypoint for Laravel
CMD php artisan config:cache; php artisan route:cache; php artisan view:cache; php-fpm --nodaemonize
