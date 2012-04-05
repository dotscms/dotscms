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
    public function init(Manager $moduleManager){

    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload/classmap.php',
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
    public function getConfig(){
        $definitions = include __DIR__ . '/config/module.di.config.php';
        $config = include __DIR__ . '/config/module.config.php';
        $config = array_merge_recursive($definitions, $config);
        return $config;
    }

}
