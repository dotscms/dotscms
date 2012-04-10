<?php
/**
 * @namespace
 */
namespace ZeAuth\Plugin;
use ZeAuth\Module,
    Zend\Mvc\Controller\Plugin\AbstractPlugin,
    Zend\View\Helper,
    Zend\View\Renderer;

/**
 * @uses ZeAuth\Module
 */
class Auth extends AbstractPlugin implements Helper
{
    /**
     * @var \ZeAuth\Service\Auth
     */
    protected $service = null;
    protected $view = null;

    /**
     * Plugin constructor
     */
    public function __construct(){
        $this->service = Module::locator()->get('ze-auth-service_auth');
    }

    /**
     * @return bool
     */
    public function isLoggedIn(){
        return $this->service->isLoggedIn();
    }

    /**
     * @return mixed
     */
    public function user(){
        return $this->service->getLoggedUser();
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