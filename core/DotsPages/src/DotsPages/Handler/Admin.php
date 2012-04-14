<?php
namespace DotsPages\Handler;

use DotsPages\Module,
    Zend\EventManager\Event;

class Admin
{
    public static function renderNav(Event $event)
    {
        $view = Module::locator()->get('view');
        $context = Module::context();
        $routeMatch = $context->getRouteMatch()->getMatchedRouteName();
        $params = array();
        $params['editable'] = ($routeMatch == 'dots-page');
        //append css code
        $view->plugin('headLink')->appendStylesheet('/css/lib/dots/pages/admin.css');
        //append javascript code
        $view->plugin('headScript')->appendFile('/js/lib/dots/pages/admin.js');
        $view->plugin('headScript')->appendScript(<<<END
    $(function(){Dots.Pages.Admin.init();});
END
);
        //render admin navigation
        return $view->render('dots-pages/admin/nav', $params);
    }
}