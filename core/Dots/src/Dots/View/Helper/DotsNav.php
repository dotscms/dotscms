<?php
namespace Dots\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\EventManager\Event;
use Dots\EventManager\GlobalEventManager;

class DotsNav extends AbstractHelper
{
    /**
     * Render the administrator navigation bar
     * @return DotsNav
     */
    public function __invoke()
    {
        GlobalEventManager::attach('head.pre', function(Event $event){
            $event->getTarget()->plugin('headScript')
                ->appendFile('/assets/default/js/html5.js')
                ->appendFile('/assets/default/js/jquery.min.js')
                ->appendFile('/assets/default/js/jquery-ui.min.js')
                ->appendFile('/assets/bootstrap/js/bootstrap.min.js')
                ->appendFile('/assets/default/js/underscore.min.js')
                ->appendFile('/assets/default/js/json2.js')
                ->appendFile('/assets/default/js/backbone.min.js');
        });
        // trigger the head.pre event
        GlobalEventManager::trigger('head.pre', $this->view);

        // trigger the head.post event
        GlobalEventManager::trigger('head.post', $this->view);

        // do not render anything if not logged in
        if (!$this->view->plugin("auth")->isLoggedIn()) {
            return '';
        }
        GlobalEventManager::attach('admin.head.pre', function(Event $event)
        {
            $event->getTarget()->plugin('headScript')
                ->appendFile('/assets/default/js/jquery.form_new.js')
                ->appendFile('/assets/default/js/jquery.json.js')
                ->appendFile('/assets/dots/js/dots.js');
        });

        GlobalEventManager::attach('admin.head.post', function(Event $event)
        {
            $event->getTarget()->plugin('headScript')
                ->appendScript('$(function(){Dots.Events.trigger("init");})');
        });

        // trigger the head.pre event
        GlobalEventManager::trigger('admin.head.pre', $this->view);

        // trigger the head.post event
        GlobalEventManager::trigger('admin.head.post', $this->view);

        // render the navigation bar
        return $this->view->render('dots/helpers/dots-nav/admin/main');
    }

}