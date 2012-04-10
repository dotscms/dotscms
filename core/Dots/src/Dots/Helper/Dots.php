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
     * Plugin constructor
     */
    public function __construct()
    {

    }

    public function adminNav()
    {
        if ($this->view->plugin("auth")->isLoggedIn()){
            return $this->view->render('dots/admin/nav');
        }
    }

    /**
     * Set the View object
     *
     * @param  \Zend\View\Renderer $view
     * @return \Zend\View\Helper
     */
    public function setView(Renderer $view)
    {
        $this->view = $view;
    }

    /**
     * Get the View object
     *
     * @return \Zend\View\Renderer
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Return the current object at invoke
     * @return Auth
     */
    public function __invoke()
    {
        return $this;
    }

}