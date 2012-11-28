<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsNavBlock;

use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

/**
 * DotsNavBlock module
 */
class Module
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
            $viewModel->setTemplate('dots-nav-block/admin/menu');
            $viewModel->setTerminal(true);
            return $view->render($viewModel);
        });
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