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

use Zend\ModuleManager\ModuleManager;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;

/**
 * Dots pages module
 */
class Module
{
    private $dispatcher = null;

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()->attach('Zend\\Mvc\\Application', 'bootstrap', array($this, 'initDispatcher'), 1000);
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initDispatcher(Event $e)
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * Get core configuration array
     * @return array
     */
    public function getConfig(){
        return include __DIR__ . '/config/module.config.php';
    }

}
