<?php

namespace Core;

use Zend\Module\Manager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{

    /**
     * Start point for any module
     * @param \Zend\Module\Manager $moduleManager
     */
    public function init(Manager $moduleManager)
    {
//        $events = StaticEventManager::getInstance();
//        $events->attach('Zend\Mvc\Application', 'route', array($this, 'initializeCore'), -100000);
    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}
