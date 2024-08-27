<?php

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

// Set the absolute path to the WordPress directory
if (!defined('ABSPATH')) {
    define('ABSPATH', '/usr/src/wordpress/');
}

// Load WordPress and the testing environment
require_once ABSPATH . 'wp-load.php';
require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Load the WordPress test functions and bootstrap
require_once ABSPATH . 'wp-tests-config.php';  // Assuming you have a wp-tests-config.php in the root
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/bootstrap.php';
