<?php
namespace Ze\Application;

use Zend\Mvc\ApplicationInterface;
/**
 * Application Context Instance
 * Contains most of the configured information used when running the application.
 */
class Context
{
    /**
     * @var Context
     */
    private static $_instance = null;
    /**
     * @var \Zend\Mvc\AppContext
     */
    private $application = null;

    /**
     * @static
     * @return Context
     */
    public static function instance()
    {
        if (!static::$_instance){
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    /**
     * @param \Zend\Mvc\AppContext $application
     */
    public function setApplication(ApplicationInterface $application)
    {
        $this->application = $application;
    }

    /**
     * @return \Zend\Mvc\AppContext
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return \Zend\Di\Locator
     */
	public function locator(){
        return $this->application->getLocator();
    }
}