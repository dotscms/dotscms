<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsPages;
use Zend\EventManager\StaticEventManager;

class Dispatcher
{
    protected $listeners = array();
    protected $handlers = array(
        'DotsPages\Handler\Admin'=>array(
            array(
                'event' => 'dots:admin.head.pre',
                'handler' => 'appendHead',
                'priority' => 100
            ),
            array(
                'event' => 'dots:admin.menu',
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
                $event = explode(':',$options['event']);
                if (array_key_exists('priority', $options)){
                    $events->attach($event[0], $event[1], array($class, $options['handler']), $options['priority']);
                }else{
                    $events->attach($event[0], $event[1], array($class, $options['handler']) );
                }
            }
        }
    }

}