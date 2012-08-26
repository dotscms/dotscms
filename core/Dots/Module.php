<?php

namespace Dots;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\Mvc\MvcEvent;

/**
 * Dots module
 */
class Module implements AutoloaderProviderInterface
{
    private static $serviceLocator;
    private static $context = null;
    private static $blockManager = null;

    /**
     * Start point for any module
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()->attach('Zend\Mvc\Application', 'bootstrap', array($this, 'setupLocator'), 1000);
    }

    public function onBootstrap(MvcEvent $event)
    {
        static::$serviceLocator = $locator = $event->getApplication()->getServiceManager();
        $config = $locator->get('Configuration');
        foreach($config['view_manager']['helper_map'] as $alias=>$class){
            $locator->get('ViewHelperManager')->setInvokableClass($alias, $class);
        }
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function setupLocator(Event $event)
    {
        $app = $event->getParam('application');
        static::$blockManager = $app->getServiceManager()->get('Dots\Block\BlockManager');
//        $app->events()->attach('dispatch', array($this, 'setupContext'));
        $app->getEventManager()->attach('render', array($this, 'registerJsonStrategy'), 100);
    }

    public function registerJsonStrategy(Event $event)
    {
        $app = $event->getTarget();
        $locator = $app->getServiceManager();
        $view = $locator->get('Zend\View\View');
        $jsonStrategy = $locator->get('Zend\View\Strategy\JsonStrategy');
        $view->getEventManager()->attach($jsonStrategy, 200);
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
    public function getConfig()
    {
        $definitions = include __DIR__ . '/config/module.di.config.php';
        $config = include __DIR__ . '/config/module.config.php';
        $config = array_merge_recursive($definitions, $config);
        return $config;
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    /**
     * Return the Dependency Injector object loaded in the application
     * @static
     * @return
     */
    public static function getServiceLocator()
    {
        return static::$serviceLocator;
    }

    /**
     * @static
     * @return \Dots\Block\BlockManager
     */
    public static function blockManager()
    {
        return static::$blockManager;
    }

}
