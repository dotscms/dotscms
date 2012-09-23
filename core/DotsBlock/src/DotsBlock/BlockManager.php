<?php
namespace DotsBlock;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;

class BlockManager implements ServiceLocatorAwareInterface
{
    /**
     * @var null
     */
    protected $locator = null;
    /**
     * @var array
     */
    protected $contentHandlers = array();
    /**
     * @var null
     */
    protected $events = null;
    /**
     * @var array
     */
    protected $blockHandlers = array();

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param HandlerAware $contentHandler
     */
    public function addContentHandler(HandlerAware $contentHandler)
    {
        $this->contentHandlers[] = $contentHandler;
    }

    /**
     * @param $contentHandlers
     */
    public function setContentHandlers($contentHandlers)
    {
        $this->contentHandlers = $contentHandlers;
    }

    /**
     * @return array
     */
    public function getContentBlockHandlers()
    {
        if (!$this->blockHandlers) {
            $results = $this->events()->trigger('listHandlers', null);
            foreach ($results as $result) {
                $this->blockHandlers[$result->getAlias()] = $result;
            }
        }
        return $this->blockHandlers;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->locator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->locator;
    }


    /**
     * Set the event manager instance used by this context
     * @param \Zend\EventManager\EventManagerInterface $events
     * @return \DotsBlock\Twig\Extension
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     * Lazy-loads an EventManager instance if none registered.
     * @return EventManagerInterface
     */
    public function events()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager(array(
                __CLASS__,
                get_called_class(),
                'blocks'
            )));
            $this->attachDefaultEventHandlers($this->events);
        }
        return $this->events;
    }

    /**
     * @param \Zend\EventManager\EventManager $events
     */
    public function attachDefaultEventHandlers(EventManager $events)
    {
        $priority = 1;
        foreach($this->contentHandlers as $handler){
            $handler->attach($events, $priority++);
        }
    }
}