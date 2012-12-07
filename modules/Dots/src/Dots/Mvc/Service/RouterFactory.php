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
use Zend\Mvc\Service\RouterFactory as DefaultRouterFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * @author Cosmin Harangus <cosmin@dotscms.com>
 */
class RouterFactory extends DefaultRouterFactory
{
    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        $router = parent::createService($serviceLocator, $cName, $rName);

        $router->getRoutePluginManager()
               ->setServiceLocator($serviceLocator);

        return $router;
    }
}
