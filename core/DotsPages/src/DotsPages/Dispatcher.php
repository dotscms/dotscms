<?php
namespace DotsPages;
use ArrayAccess,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\Event;

class Dispatcher
{
    protected $listeners = array();
    protected $handlers = array(
        'DotsPages\Handler\Admin'=>array(
            array(
                'event' => 'extend::dots/adminMenu',
                'handler' => 'renderNav'
            )
        )
    );

    public function __construct()
    {
        $this->attachDefaultListeners();
    }

    /**
     * Attach default events listeners for the dispatcher
     */
    protected function attachDefaultListeners()
    {
        $events = StaticEventManager::getInstance();
        foreach($this->handlers as $class => $handlers){
            foreach($handlers as $options){
                $event = explode('::',$options['event']);
                if (array_key_exists('priority', $options)){
                    $events->attach($event[0], $event[1], array($class, $options['handler']), $options['priority']);
                }else{
                    $events->attach($event[0], $event[1], array($class, $options['handler']) );
                }
            }
        }
    }

}