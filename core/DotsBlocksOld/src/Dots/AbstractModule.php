<?php
namespace Dots;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\Event,
    Zend\Mvc\MvcEvent,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Dots Abstract Module
 */
abstract class AbstractModule implements AutoloaderProviderInterface
{
    private static $locator;

    /**
     * Start point for any module
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {

    }

    /**
     * Initialize event listener
     * @param \Zend\Mvc\MvcEvent $e
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        self::$locator = $event->getApplication()->getServiceManager()->get('di');
    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    protected function getDefaultAutoloaderConfig($namespace=__NAMESPACE__, $dir=__DIR__)
    {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                $dir . '/autoload/classmap.php',
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $namespace => $dir . '/src/' . $namespace,
                ),
            ),
        );
    }

    public function getAutoloaderConfig(){
        return array();
    }

    /**
     * Get default configuration array
     * @return array
     */
    protected function getDefaultConfig($dir = __DIR__)
    {
        //        $definitions = include __DIR__ . '/config/module.di.config.php';
        $config = include $dir . '/config/module.config.php';
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