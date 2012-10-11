<?php
namespace DotsPages\Handler;

use Dots\Registry;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;
use DotsPages\Db\Entity\Page;

class Admin
{
    public static function appendHead(Event $event)
    {
        $serviceLocator = Registry::get('service_locator');
        $view = $serviceLocator->get('TwigViewRenderer');

        //append css code
        $view->plugin('headLink')->appendStylesheet('/assets/dots/css/admin.css');
        //append javascript code
        $view->plugin('headScript')->appendFile('/assets/dots/js/pages/admin.js');
    }

    public static function renderNav(Event $event)
    {
        $serviceLocator = Registry::get('service_locator');
        $view = $serviceLocator->get('TwigViewRenderer');
        $context = $serviceLocator->get('Application')->getMvcEvent();
        $routeMatch = $context->getRouteMatch();
        $routeMatchName = $context->getRouteMatch()->getMatchedRouteName();
        $params = array();
        $params['editable'] = ($routeMatchName == 'dots-page' || ($routeMatchName == 'home' && $routeMatch->getParam('page') instanceof Page));


        //render admin navigation
        $viewModel = new ViewModel($params);
        $viewModel->setTemplate('dots-pages/admin/nav');
        $viewModel->setTerminal(true);
        return $view->render($viewModel);
    }
}