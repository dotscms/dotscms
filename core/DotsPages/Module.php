<?php
namespace DotsPages;
use Dots\AbstractModule,
    Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\Event,
    Zend\Mvc\MvcEvent;

/**
 * Dots pages module
 */
class Module extends AbstractModule
{
    private static $dispatcher = null;
    private static $application = null;
    private static $context = null;

    public function init(ModuleManager $moduleManager)
    {
        parent::init($moduleManager);
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initDispatcher'), 1000);
    }

    public function setupContext(MvcEvent $event)
    {
        static::$context = clone $event;
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initDispatcher(Event $e)
    {
        static::$application = $app = $e->getParam('application');
        $app->events()->attach('dispatch', array($this, 'setupContext'));
        static::$dispatcher = new Dispatcher();
    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    public function getAutoloaderConfig() {
        return $this->getDefaultAutoloaderConfig(__NAMESPACE__, __DIR__);
    }

    /**
     * Get core configuration array
     * @return array
     */
    public function getConfig(){
        return $this->getDefaultConfig(__DIR__);
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
}
