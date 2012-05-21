<?php
namespace Ze;

use Zend\Mvc\Bootstrap as DefaultBootstrap,
    Zend\Mvc\Application,
    Zend\Mvc\ApplicationInterface,
    Ze\Application\Context;

class Bootstrap extends DefaultBootstrap
{
    public function bootstrap(ApplicationInterface $application = null)
    {
        if (!$application){
            $application = new Application;
        }
        parent::bootstrap($application);
        $this->setupContext($application);
        return $application;
    }

    public function setupContext(ApplicationInterface $application)
    {
        $context = Context::instance();
        $context->setApplication($application);
        $manager = $application->getLocator()->get('Zend\Session\SessionManager');
        $manager->start();
    }
}