<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

date_default_timezone_set('Europe/Bucharest');
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Setup autoloading
include 'init_autoloader.php';

$config = include 'config/application.config.php';
// set the public path in the application configuration so we know where all the public files are located
$config['public_path'] = __DIR__;

// Run the application!
Zend\Mvc\Application::init($config)->run();