<?php
/**
 * This file is part of ZeTwig
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeTwig\View;

use Zend\View\HelperBroker,
    Zend\Loader\Pluggable,
    Zend\Loader\LocatorAware,
    Zend\Di\Locator,
    Twig_Environment,
    Twig_Function_Function as TwigFunction,

    ZeTwig\View\HelperFunction,
    ZeTwig\View\Resolver;

/**
 * ZeTwig Environment class
 * @package ZeTwig
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class Environment extends Twig_Environment implements Pluggable, LocatorAware
{
    /**
     * Zend View access broker for all the provided Zend Framework helpers
     * @var null
     */
    protected $_broker = null;
    protected $_locator = null;

    /**
     * Constructor.
     *
     * Available options:
     *
     *  * debug: When set to `true`, the generated templates have a __toString()
     *           method that you can use to display the generated nodes (default to
     *           false).
     *
     *  * charset: The charset used by the templates (default to utf-8).
     *
     *  * base_template_class: The base template class to use for generated
     *                         templates (default to Twig_Template).
     *
     *  * cache: An absolute path where to store the compiled templates, or
     *           false to disable compilation cache (default)
     *
     *  * auto_reload: Whether to reload the template is the original source changed.
     *                 If you don't provide the auto_reload option, it will be
     *                 determined automatically base on the debug value.
     *
     *  * strict_variables: Whether to ignore invalid variables in templates
     *                      (default to false).
     *
     *  * autoescape: Whether to enable auto-escaping (default to true);
     *
     *  * optimizations: A flag that indicates which optimizations to apply
     *                   (default to -1 which means that all optimizations are enabled;
     *                   set it to 0 to disable)
     *
     * @param Resolver                  $loader  A Twig_LoaderInterface instance
     * @param \Zend\View\HelperBroker   $broker  A Zend View Helper Broker instance
     * @param array                     $options An array of options
     */
    public function __construct(Resolver $loader = null, HelperBroker $broker = null, $options = array())
    {
        parent::__construct($loader, $options);
        $this->setBroker( $broker );
    }

    public function setLocator(Locator $locator)
    {
        $this->_locator = $locator;
        return $this;
    }

    /**
     * @return \Zend\Di\Di
     */
    public function getLocator()
    {
        return $this->_locator;
    }


    /**
     * The the configured options into the loader for proper loading of files based on the aliases array
     * @param $environment_options
     * @return Environment
     */
    public function setEnvironmentOptions($environment_options)
    {
        $this->getLoader()->setConfig($environment_options);
        return $this;
    }

    /**
     * Get a function by name.
     *
     * Subclasses may override this method and load functions differently;
     * so no list of functions is available.
     *
     * @param string $name function name
     *
     * @return Twig_Function|false A Twig_Function instance or false if the function does not exists
     */
    public function getFunction($name)
    {
        //try to get the function from the environment itself
        $function = parent::getFunction($name);
        if (false !== $function){
            return $function;
        }

        //if not found, try to get it from  the broker and define it in the environment for later usage
        try{
            $helper = $this->plugin($name,array());
            if (null !== $helper){
                $function = new HelperFunction($name, array('is_safe' => array('html')));
                $this->addFunction($name, $function);
                return $function;
            }
        }catch(\Exception $exception){
            // ignore the exception and try to use a defined PHP function
        }

        // return any PHP function or any of the defined valid PHP constructs
        $constructs = array('isset', 'empty');
//        if( strpos($name, '_') == 0 ){
//            $_name = substr($name, 1);
            $_name = $name;
            if ( function_exists($_name) || in_array($_name, $constructs) ) {
                $function = new TwigFunction($_name);
                $this->addFunction($name, $function);
                return $function;
            }
//        }

        // no function found
        return false;
    }

    /**
     * Get plugin broker instance
     *
     * @return Zend\Loader\Broker
     */
    public function getBroker()
    {
        if (null === $this->_broker){
            $this->_broker = new HelperBroker();
        }
        return $this->_broker;
    }

    /**
     * Set plugin broker instance
     *
     * @param  string|Broker $broker Plugin broker to load plugins
     * @return Zend\Loader\Pluggable
     */
    public function setBroker($broker)
    {
        $this->_broker = $broker;
        return $this;
    }

    /**
     * Get plugin instance
     *
     * @param  string     $plugin  Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return mixed
     */
    public function plugin($plugin, array $options = null)
    {
        $helper = $this->_broker->load($plugin, $options);
        return $helper;
    }

}
