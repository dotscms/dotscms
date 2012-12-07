<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsPages\Router;
//use Dots\Registry;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\Router\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Page implements RouteInterface, ServiceLocatorAwareInterface
{
    protected $defaults = array();
    /**
     * @var ServiceLocatorInterface
     */
    protected $routerPluginManager = null;

    /**
     * Create a new page route.
     * @param array $defaults
     */
    public function __construct(array $defaults = array())
    {
        $this->defaults = $defaults;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $routerPluginManager
     */
    public function setServiceLocator(ServiceLocatorInterface $routerPluginManager)
    {
        $this->routerPluginManager = $routerPluginManager;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->routerPluginManager;
    }


    /**
     * Create a new route with given options.
     * @static
     * @param array $options
     * @return Page|void
     * @throws \Zend\Mvc\Router\Exception\InvalidArgumentException
     */
    public static function factory($options = array())
    {
        if ($options instanceof \Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new InvalidArgumentException(__METHOD__ . ' expects an array or Traversable set of options');
        }

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($options['defaults']);
    }

    /**
     * Match a given request.
     *
     * @param \Zend\Stdlib\RequestInterface $request
     * @param null $pathOffset
     * @return null|\Zend\Mvc\Router\Http\RouteMatch|\Zend\Mvc\Router\RouteMatch
     */
    public function match(Request $request, $pathOffset = null)
    {
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        $uri = $request->getUri();
        $fullPath = $uri->getPath();

        $path = substr($fullPath, $pathOffset);
        $alias = trim($path, '/');

        $model = $this->routerPluginManager->getServiceLocator()->get('DotsPages\Db\Model\Page');
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