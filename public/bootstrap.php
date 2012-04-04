<?php
// Define base path to project
defined('BASE_PATH')
    || define('BASE_PATH', dirname(__DIR__) );

chdir(dirname(__DIR__));
date_default_timezone_set(date_default_timezone_get());

require_once (getenv('ZF2_PATH') ?: dirname(BASE_PATH).'/vendor/ZendFramework2/library') . '/Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            'Ze' => BASE_PATH.'/vendor/Ze/',
        ),
    ),

));
$appConfig = include 'config/application.config.php';

error_reporting(E_ALL);
ini_set('display_errors','on');

$listenerOptions  = new Zend\Module\Listener\ListenerOptions($appConfig['module_listener_options']);

$defaultListeners = new Zend\Module\Listener\DefaultListenerAggregate($listenerOptions);
$defaultListeners->getConfigListener()->addConfigGlobPath('config/autoload/*.config.php');

$moduleManager = new Zend\Module\Manager($appConfig['modules']);
$moduleManager->events()->attachAggregate($defaultListeners);
$moduleManager->loadModules();

//// Create application, bootstrap, and run
//$bootstrap   = new Zend\Mvc\Bootstrap($defaultListeners->getConfigListener()->getMergedConfig());
//$application = new Zend\Mvc\Application;
//$bootstrap->bootstrap($application);
//$application->run()->send();
