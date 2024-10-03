#!/usr/bin/env bash

composer install
vendor/bin/phpcs --version
vendor/bin/phpunit --version

# PHP CodeSniffer
# Configure WordPress-Coding-Standards
wpcs_dir="WordPress"
wpcs_path="${PLUGIN_DIR}/vendor/squizlabs/php_codesniffer/Standards/${wpcs_dir}"
git clone -b main https://github.com/WordPress/WordPress-Coding-Standards.git $wpcs_path
phpcsutils_path="${PLUGIN_DIR}/vendor/phpcsstandards/phpcsutils"
phpcompatibility_path="${PLUGIN_DIR}/vendor/phpcompatibility/phpcompatibility-wp"
phpcs_extra_path="${PLUGIN_DIR}/vendor/phpcsstandards/phpcsextra"
vendor/bin/phpcs --config-set installed_paths $wpcs_path, $phpcsutils_path, $phpcompatibility_path, $phpcs_extra_path
installed_standards=$(vendor/bin/phpcs -i)
if [[ ${installed_standards} != *"WordPress-Core"* ]]; then
  echo "WordPress-Coding-Standards is not added to the list of coding standards."
  exit 1
fi
vendor/bin/phpcs --config-set default_standard WordPress

# PHP Unit
bash bin/install-wp-tests.sh wordpress_test $WORDPRESS_DB_USER $WORDPRESS_DB_PASSWORD $WORDPRESS_DB_HOST $WORDPRESS_VERSION
