<?php

/**
 * This file is part of ZeDb
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeDb;

use Zend\Module\Manager,
    Zend\EventManager\Event,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

/**
 * ZeTwig Module class
 * @package ZeTwig
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class Module implements AutoloaderProvider
{
    private static $_registry = null;

    /**
     * Module initialization
     * @param \Zend\Module\Manager $moduleManager
     */
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
//        $events->attach('bootstrap', 'bootstrap', array($this, 'initDbRegistry'), 20000);
    }

    /**
     * Load full configuration options
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initDbRegistry(Event $e)
    {
        $app = $e->getParam('application');
        $locator = $app->getLocator();
        $registry = $locator->get('zedb');
        static::$_registry = $registry;
    }

    public static function getRegistry(){
        return static::$_registry;
    }

    /**
     * Get Autoloader Config
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
     * Get Module Configuration
     * @return mixed
     */
    public function getConfig()
    {
//        $definitions = include __DIR__ . '/config/module.di.config.php';
        $config = include __DIR__ . '/config/module.config.php';
//        $config = array_merge_recursive($definitions, $config);
        return $config;
    }

}
