<?php

// // Load the Composer autoloader
require dirname(__DIR__) . '/vendor/autoload.php';


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

// Load WordPress test functions
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/../');
}
require_once ABSPATH . 'wp-load.php';

// Start up the WP testing environment
require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Set up the WordPress testing environment
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/bootstrap.php';