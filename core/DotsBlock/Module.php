<?php
namespace DotsBlock;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
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
        GlobalEventManager::attach('admin.head.pre',function (Event $event){
            $view = $event->getTarget();
            $view->plugin('headScript')->appendFile('/assets/dots/js/admin.blocks.js');
        });
        GlobalEventManager::attach('admin.head.post', function(Event $event){
            $view = $event->getTarget();
            $view->plugin('headScript')->appendScript('$(function(){ Dots.Blocks.init(); });');
        });
    }

    /**
     * Get module autoloader configuration
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