<?php
namespace Dots\Block;
use Zend\Mvc\LocatorAware,
    Zend\Di\Locator,
    Zend\EventManager\EventManager,
    Zend\EventManager\EventCollection,
    Zend\EventManager\Event,

    Dots\Block\Handler\HtmlContent;

class BlockManager implements LocatorAware
{
    protected $locator = null;
    protected $contentHandlers = array();
    protected $events = null;
    protected $blockHandlers = array();

    public function __construct()
    {
        $this->contentHandlers[] = new HtmlContent();
    }

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


    public function setLocator(Locator $locator)
    {
        $this->locator = $locator;
    }

    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * Set the event manager instance used by this context
     * @param \Zend\EventManager\EventCollection $events
     * @return Extension
     */
    public function setEventManager(EventCollection $events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     * Lazy-loads an EventManager instance if none registered.
     * @return EventCollection
     */
    public function events()
    {
        if (!$this->events instanceof EventCollection) {
            $this->setEventManager(new EventManager(array(
                __CLASS__,
                get_called_class(),
                'blocks'
            )));
            $this->attachDefaultEventHandlers($this->events);
        }
        return $this->events;
    }

    public function attachDefaultEventHandlers(EventManager $events)
    {
        foreach($this->contentHandlers as $handler){
            $handler->attach($events);
        }
    }
}