<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\Twig\Helper;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author Cosmin Harangus <cosmin@dotscms.com>
 */
class Trigger
{
    /**
     * @var \Zend\EventManager\EventManager|null
     */
    protected $events = array();

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Set the event manager instance used by this context
     *
     * @param \Zend\EventManager\EventManagerInterface $events
     * @param $alias
     * @return Trigger
     */
    public function setEventManager(EventManagerInterface $events, $alias)
    {
        $this->events[$alias] = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     * Lazy-loads an EventManager instance if none registered.
     *
     * @param string $alias The alias of the class/EventManager on which to trigger the event
     * @return EventManagerInterface
     */
    public function events($alias)
    {
        if (!isset($this->events[$alias]) || !($this->events[$alias] instanceof EventManagerInterface)) {
            $this->setEventManager(new EventManager(array(
                __CLASS__,
                get_called_class(),
                $alias
            )), $alias);
            $sharedManager = $this->serviceLocator->get('SharedEventManager');
            $this->events[$alias]->setSharedManager($sharedManager);
        }
        return $this->events[$alias];
    }

    /**
     * Triggers the specified event on the defined context and return a concatenated string with the results
     *
     * @param string $eventName
     * @param mixed $target
     * @param array $argv
     * @return string
     */
    public function __invoke($eventName, $target, $argv)
    {
        $alias = 'dots-twig';
        if (strpos($eventName, ':') !== false) {
            $aux = explode(':', $eventName);
            $alias = $aux[0];
            $eventName = $aux[1];
        }

        //init the event with the target, params and name
        $event = new Event();
        $event->setTarget($target);
        $event->setParams($argv);
        $event->setName($eventName);
        $content = "";
        //trigger the event listeners
        $responses = $this->events($alias)->trigger($eventName, $event);
        //merge all results and return the response
        foreach ($responses as $response) {
            $content .= $response;
        }
        return $content;
    }
}