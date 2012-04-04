<?php
namespace ZeAuth\Service;

use Zend\Authentication\Storage\Session as AuthenticationSession,
    Zend\Authentication\Result as AuthenticationResult,
    Zend\EventManager\EventCollection,
    Zend\EventManager\EventManager,
    Zend\Mvc\MvcEvent,
    
    ZeAuth\Exception,
    ZeAuth\Module;

/**
 * ZeAuth Service class
 */
class Auth
{
    /**
     * @var \Zend\EventManager\EventCollection
     */
    private $events;
    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventCollection $events 
     * @return AppContext
     */
    public function setEventManager(EventCollection $event_manager)
    {
        $this->events = $event_manager;
        return $this;
    }

    /**
     * Retrieve the event manager
     * Lazy-loads an EventManager instance if none registered.
     * @return EventCollection
     */
    public function events()
    {
        if (!$this->events instanceof EventCollection) {
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
            $restrictedRoutes = Module::getOption('restricted_routes');
            $unrestrictedRoutes = Module::getOption('unrestricted_routes');
            // Flatten the list of restricted routes
            $_restricted = array();
            foreach($restrictedRoutes->toArray() as $routes){
                $_restricted = array_merge($_restricted, $routes);
            }
            // Flatten the list of unrestricted routes
            $_unrestricted = array();
            foreach($unrestrictedRoutes->toArray() as $routes){
                $_unrestricted = array_merge($_unrestricted, $routes);
            }
            // Skip unrestricted routes
            if (!in_array($routeName, $_restricted) || in_array($routeName, $_unrestricted)){
                return false;
            }

            // If logged in then go to the requested url
            $storage = new AuthenticationSession();
            if (!$storage->isEmpty() && $storage->read() instanceof AuthenticationResult){
                return false;
            }

            //@todo: Find a way to redirect based on the route name
//            $router = $e->getRouter();
//            $router = new \Zend\Mvc\Router\Http\TreeRouteStack();

//            $url =$e->getRequest()->getRequestUri() .$e->getRequest()->getBasePath() . $router->assemble(array(), array('name'=>'ze-auth'));
//            var_dump($url);
//            $request = \Zend\Http\Request::fromString($url);
//            $matchedRoute = $router->match($request);

            $matchedRoute->setParam('controller','ze-auth-auth');
            $matchedRoute->setParam('action','index');
            $e->setRouteMatch($matchedRoute);
            return true;
        }

    }
    
    /**
     * Validate the identity of the user based in username/email or password
     * @param array $data
     * @return mixed
     *
     * - true: If the login was successful and the result was saved in the session
     * - array: An array containing a list of error messages otherwise
     */
    public function login(array $data)
    {
        if ( array_key_exists('identity', $data) && !empty($data['identity']) ){
            $identity = $data['identity'];
        }else{
            return array('identity'=>'This field is required');
        }

        if ( array_key_exists('credential', $data) && !empty($data['credential']) ){
            $credential = $data['credential'];
        }else{
            return array('credential'=>'This field is required');
        }

        if ( $identity && $credential ){
            $model = Module::locator()->get('ze-auth-model_user');
            $identity_type = Module::getOption('identity_type');
            switch($identity_type){
                case 'username':
                    $mapper = $model->getByUsername($identity);
                    break;
                case 'email_address':
                    $mapper = $model->getByEmailAddress($identity);
                    break;
                default:
                    if ( strpos($identity,'@') === false ){
                        $mapper = $model->getByUsername($identity);
                    } else {
                        $mapper = $model->getByEmailAddress($identity);
                    }
                    break;
            }
            if (!$mapper){
                return array('identity'=>'Invalid identity specified');
            }
            $salt = $mapper->getPasswordSalt();
            $password = $mapper->getPassword();

            if (!$this->_isValidCredential($password, $salt, $credential)){
                return array('credential'=>'Invalid credential specified');
            }
            $result = new AuthenticationResult(AuthenticationResult::SUCCESS,$identity);
            $session = new AuthenticationSession();
            $session->write($result);
        }
        return true;
    }

    /**
     * Clear authenticated session
     * @return void
     */
    public function logout()
    {
        $storage = new AuthenticationSession();
        $storage->clear();
    }

    /**
     * Check to see if the user is already logged in
     * @return bool
     */
    public function isLoggedIn()
    {
        $storage = new AuthenticationSession();
        return (!$storage->isEmpty() && $storage->read() instanceof AuthenticationResult);
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
        $crypt = Module::locator()->get('ze-auth-crypt');
        $alg = Module::getOption('password_hash_algorithm');
        if ($password == $crypt->encode($alg, $credential, $salt)){
            return true;
        }
        return false;
    }

}