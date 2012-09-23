<?php
namespace Dots\EventManager;

use Zend\EventManager\GlobalEventManager as GlobalManager;
use Zend\EventManager\EventManagerInterface;

class GlobalEventManager extends GlobalManager
{
    public static function setEventCollection(EventManagerInterface $events = null)
    {
        parent::setEventCollection($events);
        static::$events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
            'dots'
        ));
    }

}