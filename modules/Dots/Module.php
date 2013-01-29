<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots;

use Zend\Mvc\MvcEvent;
use Dots\Registry;
use Dots\Twig\Helper\Trigger as TriggerHelper;
use Dots\Twig\Extension as TwigExtension;

class Module
{

    /**
     * Bootstrap the DotsCMS infrastructure by setting up the required services and rendering strategies
     * @param \Zend\Mvc\MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        // set of the service manager registry
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        Registry::set('service_locator', $serviceManager);

        // register MVC event listeners
        $listener = new Mvc\Event\Listener();
        $listener->setServiceLocator($serviceManager);
        $listener->attach($app->getEventManager());

        // register json strategy to return json encoded strings where needed
        $jsonStrategy = $serviceManager->get('Zend\View\Strategy\JsonStrategy');
        $view = $serviceManager->get('Zend\View\View');
        $view->getEventManager()->attach($jsonStrategy, 200);
    }

    /**
     * Get dots configuration file
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get service manager configuration
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'aliases'=>array(
                'DotsTwigViewRenderer'=>'ZfcTwigRenderer'
            ),
            'factories'=>array(
                'DotsTwigTriggerHelper' => function ($sm){
                    $helper = new TriggerHelper($sm);
                    return $helper;
                },
                'DotsTwigExtension' => function ($sm){
                    $extension = new TwigExtension($sm);
                    return $extension;
                }
            )
        );
    }

}
