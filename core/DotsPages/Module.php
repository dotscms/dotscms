<?php
namespace DotsPages;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;

/**
 * Dots pages module
 */
class Module implements AutoloaderProviderInterface
{
    private static $dispatcher = null;
    private static $application = null;
    private static $context = null;
    private static $locator = null;

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()->attach('Zend\\Mvc\\Application', 'bootstrap', array($this, 'initDispatcher'), 1000);
    }

    public function setupContext(MvcEvent $event)
    {
        static::$context = clone $event;
    }

    /**
     * Initialize event listener
     * @param \Zend\Mvc\MvcEvent $e
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        self::$locator = $event->getApplication()->getServiceManager();
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initDispatcher(Event $e)
    {
        static::$application = $app = $e->getParam('application');
        $app->getEventManager()->attach('dispatch', array($this, 'setupContext'));
        static::$dispatcher = new Dispatcher();
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
    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return \DotsPages\Dispatcher
     */
    public function dispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * @return \Zend\Mvc\MvcEvent
     */
    public function context()
    {
        return static::$context;
    }

    /**
     * @return \Zend\Mvc\ApplicationInterface
     */
    public function application()
    {
        return static::$application;
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
