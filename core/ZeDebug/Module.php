<?php
namespace ZeDebug;

use Zend\Module\Manager,
    Zend\Module\ModuleEvent,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\Event,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    /**
     * Initialize the module by attaching different events
     * @param \Zend\Module\Manager $moduleManager
     * @return void
     */
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initListener'));
    }
    
    /**
     * Return an array with autoload options
     * @return array
     */
    public function getAutoloaderConfig()
    {
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
     * Return module configuration settings
     * @param string $env
     * @return array
     */
    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initListener(Event $e)
    {
//        $app          = $e->getParam('application');
//        $locator = $app->getLocator();
//        $view = $locator->get('view');
//        // $view is an instance of PhpRenderer
//        $broker = $locator->get('Zend\View\HelperBroker');
//        $loader = $broker->getClassLoader();
//
//        // Register singly:
//        $loader->registerPlugin('zedebug', 'ZeDebug\View\Helper\Debug');
    }

    /******************
     * STATIC METHODS *
     ******************/

    /**
     * Return a particular configuration option
     * @static
     * @param $option
     * @return null
     */
    public static function getOption($option)
    {
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }

    /**
     * Return the Dependency Injector object loaded in the application
     * @static
     * @return
     */
    public static function locator(){
        return self::$locator;
    }
}