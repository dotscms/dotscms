<?php
namespace Ze;

use Zend\Mvc\Bootstrap as DefaultBootstrap,
    Zend\Mvc\Application,
    Zend\Mvc\AppContext,
    Ze\Application\Context;

class Bootstrap extends DefaultBootstrap
{
    public function bootstrap(AppContext $application = null)
    {
        if (!$application){
            $application = new Application;
        }
        parent::bootstrap($application);
        $this->setupContext($application);
        return $application;
    }

    public function setupContext(AppContext $application)
    {
        $context = Context::getInstance();
        $context->setApplication($application);
        $manager = $application->getLocator()->get('Zend\Session\SessionManager');
        $manager->start();
    }
}