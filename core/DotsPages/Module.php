<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()->attach('Zend\\Mvc\\Application', 'bootstrap', array($this, 'initDispatcher'), 1000);
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initDispatcher(Event $e)
    {
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

}
