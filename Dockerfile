# ==============================================================================
# STAGE 1: Base
# ==============================================================================
FROM php:8.3-fpm as base

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libcurl4-openssl-dev \
    libevent-dev \
    libssl-dev \
    libprotobuf-dev \
    protobuf-compiler \
    libboost-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# 2. Configure and Install GD (and other standard extensions)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    intl \
    zip \
    gd \
    bcmath \
    sockets \
    pdo_mysql


# 3. Install PECL extensions
# raphf: Required by pecl_http
RUN pecl install raphf && docker-php-ext-enable raphf
RUN pecl install pecl_http && docker-php-ext-enable http

WORKDIR /var/www

# ==============================================================================
# STAGE 2: Development
# ==============================================================================
FROM base as dev

# Install Dev-only tools
RUN apt-get update && apt-get install -y git nodejs npm

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug
# RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#     && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# In Dev, we don't COPY code here. We bind-mount it via docker-compose.yml.

# ==============================================================================
# STAGE 3: The Builder (Compiles Assets for Prod)
# ==============================================================================
FROM base as builder

# Install building tools
RUN apt-get update && apt-get install -y git nodejs npm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# 1. Install PHP Dependencies (No Dev tools)
# Currently ignoring ext-mysql_xdevapi requirement
RUN composer install --ignore-platform-req=ext-mysql_xdevapi --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 2. Build Frontend Assets (Vite/Mix)
RUN npm install && npm run build

# ==============================================================================
# STAGE 4: Production
# ==============================================================================
FROM base as production

# Copy ONLY the files we need from the "builder" stage
# This leaves behind the heavy 'node_modules', 'git', and source caches.
COPY --from=builder /var/www ./

RUN rm -rf node_modules .git .github

# CREATE THE Laravel SYMLINK
RUN ln -s /var/www/storage/app/public /var/www/public/storage

# Start the server
CMD ["php-fpm"]
