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