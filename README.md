![Accredible Logo](https://s3.amazonaws.com/accredible-cdn/accredible_logo_sm.png)

# Accredible LearnDash Add-on

## Overview
The “Accredible LearnDash Add-on” allows you to issue [credentials](https://www.credential.net/10000005, "credentials"), certificates, and badges to your students when they complete your course. [Accredible](http://accredible.com "Accredible") credentials are:

* Easy to design with our drag and drop certificate builder
* Shareable, transferable, verifiable, and OpenBadge compliant
* Blockchain secured
* Easily shared on LinkedIn with just 1 click

Your course content is valuable, and your learners are proud to share their achievements. Make is easy for them.

**Note:** You will need an Accredible account to use this add-on. [Check out our many features](https://www.accredible.com/solutions/more-features, "More features") to see if this is right for you. You will also need the [LearnDash plugin](http://www.learndash.com "LearnDash") v3.6 or higher installed.

For instructions to set up this add-on, visit our [Help Center](https://help.accredible.com/, "Help Center").

## Compatability

Tested on Wordpress 5.9+.

## Installation

1. Visit https://accredible.com to obtain an API key.
2. Install, activate and configure the [LearnDash plugin](http://www.learndash.com/ "LearnDash") in WordPress.
3. Install and activate the Accredible LearnDash Add-on plugin in WordPress.
4. Go to the plugin settings, input your API key and select the server region of your Accredible account.
5. Ensure if the settings page says “Integraion is up and running“.

Auto issuance configuration:

1. Go to the 'Auto Issuance' page in the Wordpress admin menu.
2. Click the 'New Configuration' button.
3. Select a trigger of credential issuance, a target resource such as a course for course completion, and an Accredible group to issue credentials to.
4. Click the 'Save' button.

## Frequently Asked Questions
### How do I get an API key? 

Visit https://accredible.com to obtain a free API key.

### Which server region should I select?

If the domain of your Accredible account is "eu.dashboard.accredible.com", you need to select "EU". Otherwise, please select "US".

### Where should I report issues or bugs?

You can report any issues or bugs on our project [GitHub](https://github.com/accredible/accredible-learndash-add-on/issues "GitHub") site.

---

## Development Information

### Development setup

- ⚠ Be careful to **UNINSTALL** the plugin in this docker environment since **WordPress completely removes your local plugin directory** on uninstallation including `.git` so you will lose all your work in the directory. (In contrast, activation and deactivation of the plugin do not trigger any dangerous operations.)
- If you want to use the Accredible API in development, you can specify the endpoint as the environment variable `ACCREDIBLE_LEARNDASH_API_ENDPOINT` in `docker-compose.yml` or somewhere else so that the plugin will use it for API calls.
  - If you're using Docker Desktop for Mac in development, you need to specify `http://host.docker.internal:3000/v1` intead of `http://localhost:3000/v1` to access your local API.

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
