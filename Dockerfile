# Use a stable Ubuntu version
FROM ubuntu:22.04

# Set non-interactive mode for apt
ENV DEBIAN_FRONTEND=noninteractive

# Ensure system packages are updated properly
RUN apt-get update && apt-get -y upgrade

# Install required dependencies
RUN apt-get install --no-install-recommends -y \
    apache2 \
    apache2-dev \
    bison \
    build-essential \
    curl \
    enchant \
    git \
    libbz2-dev \
    libcurl4-openssl-dev \
    libedit-dev \
    libenchant-dev \
    libfreetype6-dev \
    libgmp-dev \
    libicu-dev \
    libjpeg-dev \
    libpng-dev \
    libpspell-dev \
    libreadline-dev \
    libsnmp-dev \
    libssl-dev \
    libtidy-dev \
    libvpx-dev \
    libxml2-dev \
    libxslt1-dev \
    re2c \
    php-pear \
    php-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Fix GMP header issue (only create if it doesn’t exist)
RUN test -f /usr/include/gmp.h || ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h

# Install mcrypt via PECL (since it's deprecated in Ubuntu repositories)
RUN pecl install mcrypt-1.0.4 && echo "extension=mcrypt.so" > /etc/php/8.1/cli/conf.d/20-mcrypt.ini || true

# Clone the PHP source repository
RUN git clone --depth=1 https://github.com/php/php-src.git /usr/local/src/php

# Compile PHP7
RUN cd /usr/local/src/php && ./buildconf && ./configure \
    --prefix=/usr/local/php70 \
    --with-config-file-path=/usr/local/php70 \
    --with-config-file-scan-dir=/usr/local/php70/conf.d \
    --with-apxs2=/usr/bin/apxs2 \
    --enable-fpm \
    --without-pear \
    --with-openssl \
    --enable-mbstring \
    --enable-exif \
    --with-gd \
    --enable-gd-native-ttf \
    --with-jpeg \
    --with-png \
    --enable-zip \
    --enable-soap \
    --with-curl \
    --enable-sockets \
    --enable-intl \
    --with-mysqli \
    --with-pdo-mysql \
    --enable-bcmath && make -j$(nproc) && make install

# Configure Apache
RUN mkdir -p /www/public
COPY apache-config-2.conf /etc/apache2/sites-enabled/000-default-2.conf
RUN echo "\n<FilesMatch \\.php$>\nSetHandler application/x-httpd-php\n</FilesMatch>" >> /etc/apache2/apache2.conf

# Enable Apache prefork module
RUN a2dismod mpm_event && a2enmod mpm_prefork && service apache2 restart

# Set up Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["/bin/bash"]
