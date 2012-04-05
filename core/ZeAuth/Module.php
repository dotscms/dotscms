<?php
namespace ZeAuth;

use Zend\Module\Manager,
    Zend\Module\ModuleEvent,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\Event,
    Zend\Module\Consumer\AutoloaderProvider,

    ZeAuth\Event\Listener;

class Module implements AutoloaderProvider
{
    const PRIORITY = 10000;
    protected static $options;
    protected static $locator;

    /**
     * Initialize the module by attaching different events
     * @param \Zend\Module\Manager $moduleManager
     * @return void
     */
    public function init(Manager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'postInit'));
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initListener'), self::PRIORITY);
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
        $app          = $e->getParam('application');
        self::$locator = $app->getLocator();
        $eventListener = new Listener();
        $app->events()->attachAggregate($eventListener);
    }

    /**
     * Load full configuration options
     * @param \Zend\Module\ModuleEvent $e
     * @return void
     */
    public function postInit(ModuleEvent $e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config['ze-auth'];
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