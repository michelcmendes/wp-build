<?php

// // Load the Composer autoloader
// require dirname(__DIR__) . '/vendor/autoload.php';

// // Bootstrap WordPress
// define('WP_INSTALLING', true);
// require_once dirname(__DIR__) . '/wordpress/wp-load.php';

// Set up the WordPress environment
define('WP_TESTS_DOMAIN', 'example.org');
define('WP_TESTS_EMAIL', 'admin@example.org');
define('WP_TESTS_TITLE', 'Test Blog');
define('WP_PHP_BINARY', 'php');
define('DB_NAME', 'wordpress');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('WP_DEBUG', true);

// Set up the WordPress database
// global $wpdb;
// $wpdb->query('CREATE TABLE wp_options (option_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, option_name VARCHAR(191) NOT NULL DEFAULT "", option_value LONGTEXT NOT NULL, autoload VARCHAR(20) NOT NULL DEFAULT "yes", PRIMARY KEY  (option_id), UNIQUE KEY option_name (option_name))');

// Load the WordPress test environment.
$_tests_dir = getenv('WP_PHPUNIT__DIR') ?: '/tmp/wordpress-tests-lib';

require_once $_tests_dir . '/includes/functions.php';

tests_add_filter('muplugins_loaded', function() {
    // Manually load the plugin being tested.
    require dirname(__FILE__) . '/../wp-content/plugins/your-plugin.php';
});

require $_tests_dir . '/includes/bootstrap.php';
