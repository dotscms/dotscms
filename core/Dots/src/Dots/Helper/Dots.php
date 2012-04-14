<?php
/**
 * @namespace
 */
namespace Dots\Helper;
use ZeAuth\Module,
    Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\View\Helper,
    Zend\View\Renderer;

/**
 * @uses Dots\Module
 */
class Dots extends AbstractPlugin implements Helper
{
    protected $view = null;

    /**
     * Render the administrator navigation bar
     * @return string
     */
    public function adminNav()
    {
        // do not render anything if not logged in
        if ( !$this->view->plugin("auth")->isLoggedIn() ){
            return '';
        }
        // add the stylesheet and render the admin navigation bar
        $this->view->plugin('headLink')->appendStylesheet('css/lib/dots/admin.css');
        // add the javascript
        $this->view->plugin('headScript')->appendFile('/js/jquery.form.js');
        $this->view->plugin('headScript')->appendFile('/js/lib/dots/admin.js');
        return $this->view->render('dots/admin/nav');
    }

    /**
     * Set the View object
     * @param  \Zend\View\Renderer $view
     * @return \Zend\View\Helper
     */
    public function setView(Renderer $view)
    {
        $this->view = $view;
    }

    /**
     * Get the View object
     * @return \Zend\View\Renderer
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Return the current object at invoke
     * @return Dots
     */
    public function __invoke()
    {
        return $this;
    }

}