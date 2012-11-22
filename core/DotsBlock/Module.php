<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsBlock;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;
use Dots\Registry;
use Dots\EventManager\GlobalEventManager;
use Zend\EventManager\StaticEventManager;

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
        $events = StaticEventManager::getInstance();
        $events->attach('dots','admin.menu',function (){
            $serviceLocator = Registry::get('service_locator');
            $view = $serviceLocator->get('TwigViewRenderer');
            $blockManager = Registry::get('block_manager');
            $handlers = $blockManager->getContentBlockHandlers();
            //render admin navigation
            $viewModel = new ViewModel(array('handlers'=> $handlers));
            $viewModel->setTemplate('dots-block/admin/nav');
            $viewModel->setTerminal(true);
            return $view->render($viewModel);
        }, 100);
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