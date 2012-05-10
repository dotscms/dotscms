<?php
namespace DotsPages\Router;
use DotsPages\Module,
    Zend\Mvc\Router\Http\RouteInterface,
    Zend\Http\PhpEnvironment\Request as PhpRequest,
    Zend\Stdlib\RequestInterface as Request,
    Zend\Mvc\Router\Http\RouteMatch,
    Zend\Loader\LocatorAware,
    Zend\Di\Locator;

class Page implements RouteInterface
{
    protected $defaults = array();

    /**
     * Create a new page route.
     *
     * @param  array  $defaults
     * @return void
     */
    public function __construct(array $defaults = array())
    {
        $this->defaults = $defaults;
    }

    /**
     * Create a new route with given options.
     *
     * @param  array|Traversable $options
     * @return Page
     */
    public static function factory($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new Exception\InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['defaults']);
    }

    /**
     * Match a given request.
     *
     * @param  Request $request
     * @return RouteMatch
     */
    public function match(Request $request)
    {
        if (!($request instanceof PhpRequest)){
            return null;
        }
        $path = $request->uri()->getPath();
        $alias = trim($path, '/');

        $model = Module::locator()->get('DotsPages\Db\Model\Page');
        $page = $model->getByAlias($alias);

        if ($page) {
            $options = $this->defaults;
            $options = array_merge($options, array('alias' => $alias, 'page' => $page));
            return new RouteMatch($options, strlen($path));
        }
        return null;
    }

    /**
     * Assemble the route.
     *
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
        if (array_key_exists('alias', $params)) {
            return '/' . $params['alias'];
        }

        if ( array_key_exists('page', $params) ){
            return '/'.$params['page']->alias;
        }
        return '/';
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        return array();
    }

}