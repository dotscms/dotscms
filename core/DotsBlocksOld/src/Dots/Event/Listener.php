<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\Event;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;

class Listener
{
    protected $listeners = array();
    public $serviceManager = null;

    /**
     * Attach events to the application and listen for the dispatch event
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return void
     */
    public function attach(SharedEventManagerInterface $events)
    {
        if (empty($this->listeners['Zend\Mvc\Application'])){
            $this->listeners['Zend\Mvc\Application'] = array();
        }
        $this->listeners['Zend\Mvc\Application'][] = $events->attach('Zend\Mvc\Application', 'bootstrap', array($this, 'setupLocator'), 1000);
    }

    /**
     * Detach all the event listeners from the event collection
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return void
     */
    public function detach($id, SharedEventManagerInterface $events)
    {
        foreach ($this->listeners[$id] as $key => $listener) {
            $events->detach($id, $listener);
            unset($this->listeners[$id][$key]);
            unset($listener);
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
        $this->serviceManager = $app->getServiceManager();
//        static::$blockManager = $app->getServiceManager()->get('Dots\Block\BlockManager');
        $app->getEventManager()->attach('render', array($this, 'registerJsonStrategy'), 100);
    }

    /**
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function registerJsonStrategy(Event $event)
    {
        $serviceManager = $event->getTarget()->getServiceManager();
        $jsonStrategy = $serviceManager->get('Zend\View\Strategy\JsonStrategy');
        $view = $serviceManager->get('Zend\View\View');
        $view->getEventManager()->attach($jsonStrategy, 200);
    }

    /**
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function onBootstrap(MvcEvent $event)
    {
        $locator = $event->getApplication()->getServiceManager();
        $config = $locator->get('Configuration');
        foreach ($config['view_manager']['helper_map'] as $alias => $class) {
            $locator->get('ViewHelperManager')->setInvokableClass($alias, $class);
        }
    }
}
