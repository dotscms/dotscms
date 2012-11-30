<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Service;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\Mvc\MvcEvent;
    
use ZeAuth\Exception;
use ZeAuth\Form\Login as LoginForm;
use ZeAuth\Crypt;

/**
 * ZeAuth Service class
 */
class Auth
{
    /**
     * @var \Zend\EventManager\EventManagerInterface
     */
    protected $events = null;
    /**
     * @var \Zend\Authentication\AuthenticationService
     */
    protected $auth = null;
    protected $serviceManager = null;
    protected $config = array();
    protected $loginForm = null;
    protected $registerForm = null;

    public function __construct()
    {
        $this->auth = new AuthenticationService();
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    public function getHomeRoute()
    {
        return $this->config['home_route'];
    }

    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventManagerInterface $events
     * @return Auth
     */
    public function setEventManager(EventManagerInterface $event_manager)
    {
        $this->events = $event_manager;
        return $this;
    }

    /**
     * Retrieve the event manager
     * Lazy-loads an EventManager instance if none registered.
     * @return EventManagerInterface
     */
    public function events()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager(array(
                __CLASS__, 
                get_called_class(),
                'ze-auth'
            )));
            $this->attachDefaultListeners();
        }
        return $this->events;
    }

    /**
     * Attach Default Listeners to the event manager
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events();
        $events->attach('restrictAccess', array($this, '_restrictAccess'));
    }

    /**
     * @param \Zend\Mvc\MvcEvent $e
     */
    public function restrictAccess(MvcEvent $e)
    {
        $this->events()->trigger('restrictAccess', $e);
    }
    
    /**
     * Restrict access to all specified routes in the config file
     * @param \Zend\Mvc\MvcEvent $e
     * @return mixed
     */
    public function _restrictAccess(MvcEvent $e)
    {
        $matchedRoute = $e->getRouteMatch();
        
        if ($matchedRoute){
            $routeName = $matchedRoute->getMatchedRouteName();
            $restrictedRoutes = $this->config['restricted_routes'];
            $unrestrictedRoutes = $this->config['unrestricted_routes'];
            // Flatten the list of restricted routes
            $_restricted = array();
            foreach($restrictedRoutes as $routes){
                $_restricted = array_merge($_restricted, $routes);
            }
            // Flatten the list of unrestricted routes
            $_unrestricted = array();
            foreach($unrestrictedRoutes as $routes){
                $_unrestricted = array_merge($_unrestricted, $routes);
            }
            // Skip unrestricted routes
            if (!in_array($routeName, $_restricted) || in_array($routeName, $_unrestricted)){
                return false;
            }

            // If logged in then go to the requested url
            if ($this->auth->hasIdentity()){
                return false;
            }

            //@todo: Find a way to redirect based on the route name
            $matchedRoute->setParam('controller','ZeAuth\Controller\AuthController');
            $matchedRoute->setParam('action','index');
            $e->setRouteMatch($matchedRoute);
            return true;
        }

    }

    public function getLoginForm(){
        if (!$this->loginForm){
            $this->loginForm = new LoginForm($this->config);
        }
        return $this->loginForm;
    }
    
    /**
     * Save identity into sesssion
     * @param array $data
     */
    public function login(array $data)
    {
        $identity = $data['identity'];

        //on successfull login save the identity in the storage
        if ($this->auth->hasIdentity()){
            $this->auth->clearIdentity();
        }
        $this->auth->getStorage()->write($identity);
    }

    /**
     * Get the logged user
     * @return \ZeAuth\Db\MapperInterface
     */
    public function getLoggedUser()
    {
        $identity = $this->auth->getIdentity();
        return $this->getUserByIdentity($identity);
    }

    /**
     * Get a user by the configured identity type
     * @param $identity
     * @return \ZeAuth\Db\MapperInterface
     */
    protected function getUserByIdentity($identity)
    {
        $model = $this->serviceManager->get('ZeAuthModelUser');
        $identity_type = $this->config['identity_type'];
        switch ($identity_type) {
            case 'username':
                $mapper = $model->getByUsername($identity);
                break;
            case 'email_address':
                $mapper = $model->getByEmailAddress($identity);
                break;
            default:
                if (strpos($identity, '@') === false) {
                    $mapper = $model->getByUsername($identity);
                } else {
                    $mapper = $model->getByEmailAddress($identity);
                }
                break;
        }
        return $mapper;
    }

    /**
     * Clear authenticated session
     * @return void
     */
    public function logout()
    {
        $this->auth->clearIdentity();
    }

    /**
     * Check to see if the user is already logged in
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->auth->hasIdentity();
    }

    /**
     * @todo: Register a new user
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Test if the credential is valid based on the configured algorithm
     * @param string $password
     * @param string $salt
     * @param string $credential
     * @return bool
     */
    protected function _isValidCredential($password, $salt, $credential)
    {
        $crypt = new Crypt();
        $alg = $this->config['password_hash_algorithm'];
        if ($password == $crypt->encode($alg, $credential, $salt)){
            return true;
        }
        return false;
    }

}