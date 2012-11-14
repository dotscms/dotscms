<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', __DIR__);
defined('BASE_PATH')
    || define('BASE_PATH', dirname(PUBLIC_PATH));

defined('IMAGE_PATH')
    || define('IMAGE_PATH', realpath(__DIR__."/data/uploads")."/");

date_default_timezone_set('Europe/Bucharest');
error_reporting(E_ALL);
ini_set('display_errors', 'on');

chdir(dirname(__DIR__));

// Setup autoloading
include 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(include 'config/application.config.php')->run();