# accredible-learndash-add-on

---

## Development Information

### Development setup

- ⚠ Be careful to **UNINSTALL** the plugin in this docker environment since **WordPress completely removes your local plugin directory** on uninstallation including `.git` so you will lose all your work in the directory. (In contrast, activation and deactivation of the plugin do not trigger any dangerous operations.)
- If you want to use the Accredible API in development, you can specify the endpoint as the environment variable `ACCREDIBLE_LEARNDASH_API_ENDPOINT` in `docker-compose.yml` or somewhere else so that the plugin will use it for API calls.

#### Prerequisites

- [Docker](https://www.docker.com/)

#### Step 1: Run Docker containers

Build and run docker containers with `docker-compose`.

```
docker-compose up -d
```

#### Step 2: Update DB user's previleges

Log into the MySQL container as the root user (password: `wordpress`). 

```
docker exec -it accredible-learndash-add-on_db_1 mysql -u root -p
```

Update `wordpress` user's privileges to create a test database at Step 3.

```
mysql> grant ALL PRIVILEGES ON *.* TO 'wordpress';
```

#### Step 3: Set up PHPUnit & PHP CodeSniffer

Log into the WordPress container and go to the plugin directory.

```
docker exec -it accredible-learndash-add-on_wordpress_1 bash
cd $PLUGIN_DIR
```

Run the following setup commands:

```
bash bin/init-wp-dev.sh
composer install
```

### WordPress

After the setup, WordPress should be running on port `8000`  of your Docker Host. Open it in a web browser and complete the WordPress installation.

### Test

This plugin uses [PHPUnit](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/) for the unit tests.

#### Run PHPUnit

Log into the WordPress container and go to the plugin directory.

```
docker exec -it accredible-learndash-add-on_wordpress_1 bash
cd $PLUGIN_DIR
```

Run unit tests of this plugin using the following command:

```
vendor/bin/phpunit
```

### Coding style

This plugin is trying to be consistent and follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).

It uses [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to check if the code meets the standards.

#### Run PHP CodeSniffer

Log into the WordPress container and go to the plugin directory.

```
docker exec -it accredible-learndash-add-on_wordpress_1 bash
cd $PLUGIN_DIR
```

Run PHP CodeSniffer using the following command:

```
vendor/bin/phpcs
```

Certain sniff violations can be automatically fixed with PHP Code Beautifier and Fixer (PHPCBF):

```
vendor/bin/phpcbf
```

### phpMyAdmin

You can access phpMyAdmin at port `8082` of your Docker Host and log in with:

```
WORDPRESS_DB_USER : wordpress
WORDPRESS_DATABASE_PASSWORD: wordpress
```
