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
use Dots\Registry,
    Zend\Mvc\Router\Http\RouteInterface,
    Zend\Http\PhpEnvironment\Request as PhpRequest,
    Zend\Stdlib\RequestInterface as Request,
    Zend\Mvc\Router\Http\RouteMatch,
    Zend\Mvc\Router\Exception\InvalidArgumentException,
    Zend\Stdlib\ArrayUtils;

class Page implements RouteInterface
{
    protected $defaults = array();

    /**
     * Create a new page route.
     * @param array $defaults
     */
    public function __construct(array $defaults = array())
    {
        $this->defaults = $defaults;
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

        $model = Registry::get('service_locator')->get('DotsPages\Db\Model\Page');
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