<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZeAuth\Service\Auth;

/**
 * ZeAuth Service factory
 * @package ZeAuth
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class AuthFactory implements FactoryInterface
{

    /**
     * Create and return a Auth instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Auth
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $config = isset($config['ze-auth']) && (is_array($config['ze-auth']) || $config['ze-auth'] instanceof ArrayAccess)
            ? $config['ze-auth']
            : array();

        $auth = new Auth();
        $auth->setServiceManager($serviceLocator);
        $auth->setConfig($config);
        return $auth;
    }

}