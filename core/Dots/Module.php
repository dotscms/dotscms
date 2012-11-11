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

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Dots\Registry;

class Module implements AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        Registry::set('service_locator', $serviceManager);

        // register json strategy to return json encoded strings where needed
        $jsonStrategy = $serviceManager->get('Zend\View\Strategy\JsonStrategy');
        $view = $serviceManager->get('Zend\View\View');
        $view->getEventManager()->attach($jsonStrategy, 200);

        // set up view helper manager to allow addition of helper classes via the config file
        $config = $serviceManager->get('Configuration');
        $helperManager = $serviceManager->get('ViewHelperManager');
        $twigEnvironment = $serviceManager->get('TwigEnvironment');
        $manager = $twigEnvironment->getManager();
        foreach ($config['view_manager']['helper_map'] as $alias => $class) {
            $helperManager->setInvokableClass($alias, $class);
            $manager->setInvokableClass($alias, $class);
        }
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}
