<?php

/**
 * This file is part of ZeTwig
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeTwig;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider,
    Zend\Module\ModuleEvent;

/**
 * ZeTwig Module class
 * @package ZeTwig
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 * @todo Implement extension points to allow injection of html from other modules
 * @todo Implement rendering of controller actions from within a module
 */
class Module implements AutoloaderProvider
{
    /**
     * @var \Zend\Mvc\AppContext
     */
    protected static $application;

    /**
     * Module initialization
     * @param \Zend\Module\Manager $moduleManager
     */
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'bootstrap'), 100);
    }

    public function bootstrap($e)
    {
        // Register a "render" event, at high priority (so it executes prior
        // to the view attempting to render)
        $app = $e->getParam('application');
        static::$application = $app;
        $app->events()->attach('render', array($this, 'registerTwigStrategy'), 100);
    }

    public function registerTwigStrategy($e)
    {
        $app          = $e->getTarget();
        $locator      = $app->getLocator();
        $view         = $locator->get('Zend\View\View');
        $twigStrategy = $locator->get('ZeTwig\View\Strategy\TwigRendererStrategy');

        $renderer = $twigStrategy->getRenderer();

        $basePath = $app->getRequest()->getBasePath();
        $renderer->plugin('basePath')->setBasePath($basePath);
        $renderer->plugin('url')->setRouter($e->getRouter());
        $renderer->plugin('headTitle')->setSeparator(' - ')
                                  ->setAutoEscape(false);

        // Attach strategy, which is a listener aggregate, at high priority
        $view->events()->attach($twigStrategy, 100);
    }

    /**
     * Get Autoloader Config
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                __DIR__ . '/autoload/classmap.php',
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
                'prefixes' => array(
                    'Twig' => __DIR__ . '/vendor/Twig/lib/Twig'
                )
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

    /**
     * @static
     * @return \Zend\Mvc\AppContext
     */
    public static function getApplication()
    {
        return static::$application;
    }

}