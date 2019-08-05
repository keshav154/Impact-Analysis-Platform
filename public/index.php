<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
//error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 0);
chdir(dirname(__DIR__));
date_default_timezone_set("Asia/Kolkata");
// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Setup autoloading
require 'init_autoloader.php';

$env = strtolower(getenv('APP_ENV'));

if(empty($env)) {
    $config = 'config/application.config.php';
} else {
	$config = 'config/application.' . $env . '.config.php';
}

// Run the application!
Zend\Mvc\Application::init(require  $config)->run();
