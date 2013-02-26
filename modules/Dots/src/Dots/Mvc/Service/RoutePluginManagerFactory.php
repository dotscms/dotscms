<?php
/**
 * DotsCMS (http://www.dotscms.com/)
 *
 * @link      http://github.com/dotscms/dotscms for the canonical source repository
 * @copyright Copyright (c) 2012 DotsCMS (http://www.dotscms.com/)
 * @license   http://www.dotscms.com/license New BSD License
 * @package   Dots
 */
namespace Dots\Mvc\Service;
use Zend\Mvc\Service\RoutePluginManagerFactory as DefaultRoutePluginManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * @author Cosmin Harangus <cosmin@dotscms.com>
 */
class RoutePluginManagerFactory extends DefaultRoutePluginManagerFactory
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $routePluginManager = parent::createService($serviceLocator);

        if (isset($config['route_manager'])) {
            if (!empty($config['route_manager']['invokables'])){
                foreach($config['route_manager']['invokables'] as $name=>$invokableClass){
                    $routePluginManager->setInvokableClass($name, $invokableClass);
                }
            }
        }

        return $routePluginManager;
    }
}