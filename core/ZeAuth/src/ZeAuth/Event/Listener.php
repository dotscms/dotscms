<?php
namespace ZeAuth\Event;

//GLOBAL REQUIREMENTS
use ArrayAccess,
    Zend\EventManager\EventCollection,
    Zend\EventManager\ListenerAggregate,
//CLOSED REQUIREMENTS
    ZeAuth\Module;

class Listener implements ListenerAggregate
{
    const PRIORITY_RESTRICT_ACCESS = 10000;
    protected $events = array();
    protected $listeners = array();
    protected $staticListeners = array();

    /**
     * Attach events to the application and listen for the dispatch event
     * @param \Zend\EventManager\EventCollection $events
     * @return void
     */
    public function attach(EventCollection $events)
    {
        $this->listeners[] = $events->attach('dispatch', array($this, 'restrictAccess'), self::PRIORITY_RESTRICT_ACCESS);
    }

    /**
     * Detach all the event listeners from the event collection
     * @param \Zend\EventManager\EventCollection $events
     * @return void
     */
    public function detach(EventCollection $events)
    {
        foreach ($this->listeners as $key => $listener) {
            $events->detach($listener);
            unset($this->listeners[$key]);
            unset($listener);
        }
    }
    
    public function restrictAccess($e)
    {
        $service = Module::locator()->get('ze-auth-service_auth');
        $service->restrictAccess($e);
    }
    
    
}
