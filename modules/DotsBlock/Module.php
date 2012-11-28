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

use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;
use Zend\EventManager\StaticEventManager;
use Dots\Registry;
use DotsBlock\Twig\Extension;

/**
 * DotsBlock module
 */
class Module
{

    public function onBootstrap(MvcEvent $event)
    {
        $app = $event->getApplication();
        $serviceManager = $app->getServiceManager();
        $blockManager = $serviceManager->get('DotsBlockManager');
        Registry::set('block_manager', $blockManager);
        $events = StaticEventManager::getInstance();

        $events->attach('dots','admin.menu',function () use ($serviceManager){
            $view = $serviceManager->get('DotsTwigViewRenderer');
            //render admin navigation
            $viewModel = new ViewModel();
            $viewModel->setTemplate('dots-block/admin/nav');
            $viewModel->setTerminal(true);
            return $view->render($viewModel);
        }, 100);
    }

    /**
     * Get configuration array
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get service configuration
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'DotsBlockManager' => 'DotsBlock\Service\BlockManagerFactory',
                'DotsBlock\Twig\Extension' => function ($sm){
                    return new Extension($sm);
                }
            ),
            'aliases' => array(
                'DotsBlock\BlockManager' => 'DotsBlockManager'
            )
        );
    }

}