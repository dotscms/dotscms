<?php
namespace DotsSlideshow;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * DotsBlock module
 */
class Module implements AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $events = StaticEventManager::getInstance();

        $events->attach('dots', 'blocks.admin.menu', function () use ($serviceManager)
        {
            $view = $serviceManager->get('DotsTwigViewRenderer');
            //render admin navigation
            $viewModel = new ViewModel();
            $viewModel->setTemplate('dots-slideshow/admin/menu');
            $viewModel->setTerminal(true);
            return $view->render($viewModel);
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

}