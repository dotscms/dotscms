<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Helper\HelperInterface;
use Zend\View\Renderer\RendererInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * @uses ZeAuth\Module
 */
class Auth extends AbstractPlugin implements HelperInterface, ServiceManagerAwareInterface
{
    /**
     * @var \ZeAuth\Service\Auth
     */
    protected $service = null;
    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $view = null;

    /**
     * Set the ZeAuth Service for the plugin/helper class.
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     * @return \ZeAuth\Service\Auth
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        if (!$this->service){
            if ($serviceManager instanceof \Zend\View\HelperPluginManager){
                $this->service = $serviceManager->getServiceLocator()->get('ZeAuth');
            }else{
                $this->service = $serviceManager->get('ZeAuth');
            }
        }
        return $this->service;
    }


    /**
     * Test if the user is logged in
     * @return bool
     */
    public function isLoggedIn(){
        return $this->service->isLoggedIn();
    }

    /**
     * Return the logged user or null if not logged in
     * @return \ZeAuth\Db\MapperInterface
     */
    public function user(){
        return $this->service->getLoggedUser();
    }

    /**
     * Set the View object
     *
     * @param  \Zend\View\Renderer\RendererInterface $view
     * @return \Zend\View\Helper\HelperInterface
     */
    public function setView(RendererInterface $view)
    {
        $this->view = $view;
    }

    /**
     * Get the View object
     *
     * @return \Zend\View\Renderer\RendererInterface
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