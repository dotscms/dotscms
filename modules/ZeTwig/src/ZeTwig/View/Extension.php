<?php
namespace ZeTwig\View;

use
    Zend\Http\Response,
    Zend\Mvc\Controller\ActionController,
    Zend\View\Model\ViewModel,
    Zend\Loader\LocatorAware,
    Zend\Mvc\InjectApplicationEvent,
    Zend\EventManager\EventManager,
    Zend\EventManager\EventCollection,
    Zend\EventManager\Event,
    Twig_Extension,

    ZeTwig\Module,
    ZeTwig\View\Extension\Render\TokenParser as RenderTokenParser,
    ZeTwig\View\Extension\Trigger\TokenParser as TriggerTokenParser;

/**
 * Twig Extension for ZeTwig
 */
class Extension extends Twig_Extension
{
    /**
     * @var \Zend\EventManager\EventManager | null
     */
    protected $events = null;

    /**
     * Returns the name of the extension.
     * @return string The extension name
     */
    function getName()
    {
        return 'ZeTwig';
    }

    /**
     * Return a list of token parsers to register with the envirionment
     * @return array
     */
    public function getTokenParsers()
    {
        return array(
            new RenderTokenParser(),
            new TriggerTokenParser(),
        );
    }

    /**
     * Set the event manager instance used by this context
     * @param \Zend\EventManager\EventCollection $events
     * @return Extension
     */
    public function setEventManager(EventCollection $events)
    {
        $this->events = $events;
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
                'extend'
            )));
        }
        return $this->events;
    }

    /**
     * Triggers the specified event on the defined context and return a concateneted string with the results
     * @param string $eventName
     * @param mixed $target
     * @param array $argv
     * @return string
     */
    public function triggerEvent($eventName, $target, $argv)
    {
        //init the event with the target, params and name
        $event = new Event();
        $event->setTarget($target);
        $event->setParams($argv);
        $event->setName($eventName);
        $content = "";
        //trigger the event listeners
        $responses = $this->events()->trigger($eventName, $event);
        //merge all results and return the response
        foreach($responses as $response){
            $content .= $response;
        }
        return $content;
    }

    /**
     * Render an action from a controller and render it's associated template
     * @param string $expr
     * @param array $attributes
     * @param array $options
     * @return string
     */
    public function renderAction($expr, $attributes, $options)
    {
        $application = Module::getApplication();
        //parse the name of the controller, action and template directory that should be used
        if (strpos($expr, '/')>0){
            $params = explode('/',$expr);
            $controllerName = $params[0];
            $actionName = $params[1];
            $templateDir = $controllerName.'/';
        }else{
            $params = explode(':', $expr);
            $moduleName = $params[0];
            $controllerName = $params[1];
            $actionName = $params[2];
            $actionName = lcfirst($actionName);
            $actionName = strtolower(preg_replace('/([A-Z])/', '-$1', $actionName));
            $templateDir = lcfirst($moduleName).'-'.lcfirst($controllerName).'/';
            $controllerName = $moduleName.'\\Controller\\'.$controllerName.'Controller';
        }

        //instantiate the controller based on the given name
        $controller = $application->getLocator()->get($controllerName);
        //inject the locator
        if ($controller instanceof LocatorAware) {
            $controller->setLocator($application->getLocator());
        }

        //clone the MvcEvent and route and update them with the provided parameters
        $event = $application->getMvcEvent();
        $routeMatch = clone $event->getRouteMatch();
        $event = clone $event;
        foreach ($attributes as $key=>$value){
            $routeMatch->setParam($key, $value);
        }
        $event->setRouteMatch($routeMatch);

        //inject the new event into the controller
        if ($controller instanceof InjectApplicationEvent) {
            $controller->setEvent($event);
        }

        //test if the action exists in the controller and change it to not-found if missing
        $method = ActionController::getMethodFromAction($actionName);
        if (!method_exists($controller, $method)){
            $method = 'notFoundAction';
            $actionName = 'not-found';
        }
        //call the method on the controller
        $response  = $controller->$method();
        //if the result is an instance of the Response class return it
        if ($response instanceof Response){
            return $response->getBody();
        }

        //if the response is an instance of ViewModel then render that one
        if ($response instanceof ViewModel){
            $viewModel = $response;
        }elseif (is_array($response) || $response instanceof \ArrayAccess || $response instanceof \Traversable) {
            $viewModel = new ViewModel($response);
            $viewModel->setTemplate($templateDir . $actionName);
        }else{
            return '';
        }
        $viewModel->terminate();
        $viewModel->setOption('has_parent',true);

        $view = $application->getLocator()->get('Zend\View\View');
        $output = $view->render($viewModel);
        return $output;
    }

}









