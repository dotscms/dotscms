<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth;
use Zend\ModuleManager\ModuleManager,
    Zend\ModuleManager\ModuleEvent,
    Zend\EventManager\StaticEventManager,
    Zend\EventManager\Event,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\Mvc\MvcEvent,
    ZeAuth\Event\Listener;

class Module implements AutoloaderProviderInterface
{
    const PRIORITY = 10000;
    protected static $options;
    protected static $serviceManager;

    /**
     * Initialize the module by attaching different events
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     * @return void
     */
    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->getEventManager()->getSharedManager()
            ->attach('Zend\\Mvc\\Application', 'bootstrap', array($this, 'initListener'), self::PRIORITY);
    }
    
    /**
     * Return an array with autoload options
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Return module configuration settings
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get Service Manager configuration for ZeAuth module
     * @return array
     */
    public function getServiceConfig(){
        return array(
            'factories'=>array(
                'ZeAuth'=>'ZeAuth\\Service\\AuthFactory',
                'ZeAuth\LoginInputFilter'=>function($sm){
                    return new \ZeAuth\Form\LoginInputFilter($sm);
                },
            ),
        );
    }

    /**
     * Initialize event listener
     * @param \Zend\EventManager\Event $e
     * @return void
     */
    public function initListener(Event $e)
    {
        $app          = $e->getParam('application');
        $eventListener = new Listener();
        $eventListener->setServiceManager($app->getServiceManager());
        $app->getEventManager()->attachAggregate($eventListener);
    }

}