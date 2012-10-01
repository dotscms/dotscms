<?php

namespace Dots;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Dots\Event\Listener;

/**
 * Dots module
 */
class Module implements AutoloaderProviderInterface
{
    protected static $listener = null;

    /**
     * Start point for any module
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        static::$listener = new Listener();
        static::$listener->attach($moduleManager->getEventManager()->getSharedManager());
    }

    public function onBootstrap(MvcEvent $event)
    {
        static::$listener->onBootstrap($event);
    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    public function getAutoloaderConfig() {
        return array(
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
//        $definitions = include __DIR__ . '/config/module.di.config.php';
        $config = include __DIR__ . '/config/module.config.php';
//        $config = array_merge_recursive($definitions, $config);
        return $config;
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DotsBlockManager' => 'Dots\Service\BlockManagerFactory',
            ),
            'aliases' => array(
                'Dots\Block\BlockManager' => 'DotsBlockManager'
            )
        );
    }

//    /**
//     * Return the Dependency Injector object loaded in the application
//     * @static
//     * @return
//     */
    public static function getServiceLocator()
    {
        return static::$listener->serviceManager;
    }

//    /**
//     * @static
//     * @return \Dots\Block\BlockManager
//     */
    public static function blockManager()
    {
        return static::$listener->serviceManager->get('DotsBlockManager');
    }

}
