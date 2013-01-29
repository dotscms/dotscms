<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\EventManager;

use Zend\EventManager\GlobalEventManager as GlobalManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\StaticEventManager;
use Zend\EventManager\EventManager;

class GlobalEventManager extends GlobalManager
{
    public static function init()
    {
        static::setEventCollection(new EventManager());
    }

    public static function setEventCollection(EventManagerInterface $events = null)
    {
        parent::setEventCollection($events);
        $sharedManager = StaticEventManager::getInstance();
        static::$events->setSharedManager($sharedManager);
        static::$events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
            'dots'
        ));
    }

}