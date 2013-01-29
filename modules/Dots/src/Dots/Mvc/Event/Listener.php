<?php
namespace Dots\Mvc\Event;
/**
 *
 * @author Cosmin Harangus <cosmin@around25.com>
 */

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
use Zend\EventManager\StaticEventManager;
use Dots\EventManager\GlobalEventManager;

class Listener implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator = null;
    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * Dots MVC dispatch handler
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function onDispatch(MvcEvent $event)
    {
        $view = $this->getServiceLocator()->get('DotsTwigViewRenderer');
        $this->attachConfigEvents();

        // trigger the head.pre event
        GlobalEventManager::trigger('head.pre', $view);

        // trigger the head.post event
        GlobalEventManager::trigger('head.post', $view);

        //trigger the event listeners
        $this->triggerInlineEvent($view, 'body.inline');

        // do not render anything for the admin section if not logged in
        if (!$view->plugin("auth")->isLoggedIn()) {
            return true;
        }

        // trigger the admin.head.pre event
        GlobalEventManager::trigger('admin.head.pre', $view);

        // trigger the admin.head.post event
        GlobalEventManager::trigger('admin.head.post', $view);

        //trigger the event listeners
        $this->triggerInlineEvent($view, 'admin.body.inline');
        return true;
    }

    /**
     * Trigger the specified event and add the results on the view
     * @param $name
     * @return string
     */
    protected function triggerInlineEvent($view, $name)
    {
        $responses = GlobalEventManager::trigger($name, $view);
        $inline = "";
        //merge all results and return the response
        foreach ($responses as $response) {
            $inline .= $response;
        }
        StaticEventManager::getInstance()->attach('dots', 'inlineScript',
            function() use ($inline){
                return $inline;
            }
        );
    }

    /**
     * Attach scripts and links to the view helpers based on the configuration file.
     * @todo Handle priority
     * @todo Add extended specification for scripts and links
     */
    protected function attachConfigEvents()
    {
        $config = $this->serviceLocator->get('Configuration');
        if (isset($config['dots']['view']['events']) && is_array($config['dots']['view']['events'])) {
            foreach ($config['dots']['view']['events'] as $name => $options) {
                if (!empty($options)) {
                    StaticEventManager::getInstance()->attach('dots',$name, function(Event $event) use($options)
                    {
                        $view = $event->getTarget();
                        if (isset($options['scripts']) && is_array($options['scripts'])) {
                            $scripts = array_filter($options['scripts'], function ($script)
                            {
                                return $script != null;
                            });
                            foreach ($scripts as $script) {
                                $view->plugin('headScript')->appendFile($script);
                            }
                        }
                        if (isset($options['links']) && is_array($options['links'])) {
                            $links = array_filter($options['links'], function ($link)
                            {
                                return $link != null;
                            });
                            foreach ($links as $script) {
                                $view->plugin('headLink')->appendStylesheet($script);
                            }
                        }
                    }, 200);
                }
            }
        }
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('dispatch', array($this, 'onDispatch'), 10000);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Set service locator
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


}