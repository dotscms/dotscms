<?php
namespace DotsPages\Handler;

use DotsPages\Module;
use Zend\EventManager\Event;
use Zend\View\Model\ViewModel;
use DotsPages\Db\Entity\Page;

class Admin
{
    public static function renderNav(Event $event)
    {
        $view = Module::locator()->get('TwigViewRenderer');
        $context = Module::context();
        $routeMatch = $context->getRouteMatch();
        $routeMatchName = $context->getRouteMatch()->getMatchedRouteName();
        $params = array();
        $params['editable'] = ($routeMatchName == 'dots-page' || ($routeMatchName == 'home' && $routeMatch->getParam('page') instanceof Page));
        //append css code
        $view->plugin('headLink')->appendStylesheet('/assets/dots/css/admin.css');
        //append javascript code
        $view->plugin('headScript')->appendFile('/assets/dots/js/pages/admin.js');
        $view->plugin('headScript')->appendScript(<<<END
    $(function(){Dots.Pages.Admin.init();});
END
);
        //render admin navigation
        $viewModel = new ViewModel($params);
        $viewModel->setTemplate('dots-pages/admin/nav');
        $viewModel->setTerminal(true);
        return $view->render($viewModel);
    }
}