FROM wordpress:5.9-php7.4-apache

# For bin/install-wp-tests.sh
ENV WORDPRESS_VERSION 5.9
ENV PLUGIN_DIR /var/www/html/wp-content/plugins/accredible-learndash-add-on

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
      && php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
      && php composer-setup.php \
      && php -r "unlink('composer-setup.php');" \
      && sudo mv composer.phar /usr/local/bin/composer \
      && composer --version

# Configuring PHP directives
COPY custom.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www/html
