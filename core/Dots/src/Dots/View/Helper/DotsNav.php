<?php
namespace Dots\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Dots\EventManager\GlobalEventManager;

class DotsNav extends AbstractHelper
{
    /**
     * Render the administrator navigation bar
     * @return DotsNav
     */
    public function __invoke()
    {
        // do not render anything if not logged in
        if (!$this->view->plugin("auth")->isLoggedIn()) {
            return '';
        }
        // add the stylesheet and render the admin navigation bar
        $this->view->plugin('headScript')->appendFile('/assets/default/js/jquery.form.js');
        $this->view->plugin('headScript')->appendFile('/assets/default/js/jquery-ui.min.js');
        $this->view->plugin('headScript')->appendFile('/assets/default/js/jquery.json.js');
        $this->view->plugin('headScript')->appendFile('/assets/dots/js/admin.js');

        // trigger the head.pre event
        GlobalEventManager::trigger('head.pre', null, array(
            'view' => $this->view
        ));

        // trigger the head.post event
        GlobalEventManager::trigger('head.post', null, array(
            'view' => $this->view
        ));

        // render the navigation bar
        return $this->view->render('dots/helpers/dots-nav/admin/main');
    }

}