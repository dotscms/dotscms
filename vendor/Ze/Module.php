<?php
namespace Ze;

use Zend\Module\Manager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

/**
 * Ze module
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