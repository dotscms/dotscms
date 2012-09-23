<?php
namespace DotsBlock;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Dots\Registry;
use Dots\EventManager\GlobalEventManager;

/**
 * DotsBlock module
 */
class Module implements AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        $blockManager = $serviceManager->get('DotsBlockManager');
        Registry::set('block_manager', $blockManager);
        GlobalEventManager::attach('head.pre',function ($event){
            $view = $event->getParam('view');
            $view->plugin('headScript')->appendFile('/assets/dots/js/admin.blocks.js');
        });
        GlobalEventManager::attach('head.post', function($event){
            $view = $event->getParam('view');
            $view->plugin('headScript')->appendScript('$(function(){ Dots.Blocks.init(); });');
        });
    }

    /**
     * Get module autoloader configuration
     * @return array
     */
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Get core configuration array
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DotsBlockManager' => 'DotsBlock\Service\BlockManagerFactory',
            ),
            'aliases' => array(
                'DotsBlock\BlockManager' => 'DotsBlockManager'
            )
        );
    }

}