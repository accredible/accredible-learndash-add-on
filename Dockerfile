FROM wordpress:6.6-php8.2-apache

# Install wp-cli & composer
RUN apt-get update && apt-get install -y vim sudo git default-mysql-client subversion
# wp-cli for wp setups: https://wp-cli.org/#installing
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
      && php wp-cli.phar --info \
      && chmod +x wp-cli.phar \
      && sudo mv wp-cli.phar /usr/local/bin/wp \
      && wp --info
# composer for PHP package installation: https://getcomposer.org/download/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
      && php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
      && php composer-setup.php \
      && php -r "unlink('composer-setup.php');" \
      && sudo mv composer.phar /usr/local/bin/composer \
      && composer --version

# Configuring PHP directives
COPY custom.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www/html
