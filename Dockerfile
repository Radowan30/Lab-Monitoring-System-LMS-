# ===============================
# 1️⃣ Build frontend assets
# ===============================
FROM node:20-bullseye as build-frontend

WORKDIR /app

# Copy Node dependencies
COPY package.json package-lock.json ./

# Install dependencies
RUN npm install

# Copy frontend source files
COPY resources/ resources/
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

# Build frontend assets
RUN npm run build

# ===============================
# 2️⃣ Build PHP backend + install Nginx
# ===============================
FROM php:8.2-fpm-bullseye as backend

WORKDIR /var/www/html

# Install PHP extensions + Nginx
RUN apt-get update \
    && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip unzip git curl nginx \
    && docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy entire Laravel project files
COPY . .

# ✅ Copy entire public folder (including index.php, .htaccess, etc.)
COPY public/ /var/www/html/public/

# ✅ Copy built frontend assets from build stage
COPY --from=build-frontend /app/public/build /var/www/html/public/build

# ✅ Copy over Vite config files for reference (optional)
COPY --from=build-frontend /app/package.json /var/www/html/package.json
COPY --from=build-frontend /app/vite.config.js /var/www/html/vite.config.js
COPY --from=build-frontend /app/tailwind.config.js /var/www/html/tailwind.config.js
COPY --from=build-frontend /app/postcss.config.js /var/www/html/postcss.config.js

# Install PHP dependencies (prod only)
RUN composer install --optimize-autoloader --no-dev

# ✅ Set permissions for Laravel storage & cache folders
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ Copy nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# ✅ Set environment variables for Railway (or your host)
ENV PORT=8080
EXPOSE 8080

# ✅ Run Laravel caches & start services
CMD php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    service nginx start && \
    php-fpm --nodaemonize
