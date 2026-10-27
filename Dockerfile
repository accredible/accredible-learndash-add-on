FROM wordpress:6.6-php8.2-apache

# Install wp-cli & composer
RUN apt-get update && apt-get install -y vim sudo git default-mysql-client subversion
# wp-cli for wp setups: https://wp-cli.org/#installing
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
      && php wp-cli.phar --info \
      && chmod +x wp-cli.phar \
      && sudo mv wp-cli.phar /usr/local/bin/wp \
      && wp --info
# composer for PHP package installation, copied from the official image
# (the installer's pinned hash goes stale on every composer release).
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN composer --version

# Configuring PHP directives
COPY custom.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www/html
