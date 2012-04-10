<?php

namespace Dots;

use Zend\Module\Manager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

/**
 * Dots module
 */
class Module implements AutoloaderProvider
{
    private static $locator;

    /**
     * Start point for any module
     * @param \Zend\Module\Manager $moduleManager
     */
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'setupLocator'), 1000);
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function setupLocator(Event $e)
    {
        self::$locator = $e->getParam('application')->getLocator();
    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    public function getAutoloaderConfig() {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                __DIR__ . '/autoload/classmap.php',
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Get core configuration array
     * @return array
     */
    public function getConfig(){
//        $definitions = include __DIR__ . '/config/module.di.config.php';
        $config = include __DIR__ . '/config/module.config.php';
//        $config = array_merge_recursive($definitions, $config);
        return $config;
    }

    /**
     * Return the Dependency Injector object loaded in the application
     * @static
     * @return
     */
    public static function locator()
    {
        return self::$locator;
    }

}
