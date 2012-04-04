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

use Zend\View\Renderer as ViewRenderer,
    Zend\Loader\Pluggable,
    Zend\Filter\FilterChain,
    Zend\View\Resolver as ViewResolver,
    Zend\View\Model,
    Zend\View\Renderer\TreeRendererInterface,

    ZeTwig\View\Environment,
    ZeTwig\View\Resolver,
    ZeTwig\View\Exception;

/**
 * ZeTwig Renderer
 * @package ZeTwig
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class Renderer implements ViewRenderer, Pluggable, TreeRendererInterface
{
    /**
     * Twig environment
     * @var \ZeTwig\View\Environment
     */
    protected $__environment = null;
    /**
     * @var null
     */
    protected $__filterChain = null;
    protected $__templates = array();
    protected $__renderTrees = false;

    /**
     * @param \ZeTwig\View\Environment $environment
     * @param array $config Configuration options
     */
    public function __construct(Environment $environment, $config = array())
    {
        $this->__environment = $environment;
        $this->__environment->addExtension(new Extension());
    }

    /**
     * Set the resolver used to map a template name to a resource the renderer may consume.
     * @param \Zend\View\Resolver $resolver
     * @return \ZeTwig\View\Renderer
     */
    public function setResolver(ViewResolver $resolver)
    {
        $this->__environment->setLoader($resolver);
        return $this;
    }

    public function setEnvironmentOptions($options)
    {
        $this->__environment->setEnvironmentOptions($options);
    }

    /**
     * Processes a view template and returns the output.
     *
     * @param string $nameOrModel The template name to process.
     * @param array $values The variables with which to render the template
     * @return string The script output.
     */
    public function render($nameOrModel, $values = null)
    {
        if ($nameOrModel instanceof Model) {
            $model       = $nameOrModel;
            $nameOrModel = $model->getTemplate();
            if (empty($nameOrModel)) {
                throw new Exception\TemplateException(sprintf(
                    '%s: received View Model argument, but template is empty',
                    __METHOD__
                ));
            }
            $options = $model->getOptions();
            foreach ($options as $setting => $value) {
                $method = 'set' . $setting;
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
                unset($method, $setting, $value);
            }
            unset($options);

            // Give view model awareness via ViewModel helper
            $helper = $this->plugin('view_model');
            $helper->setCurrent($model);

            $values = $model->getVariables();
            if ($values instanceof \ArrayObject){
                $values = $values->getArrayCopy();
            }

            unset($model);
        }

        if (null===$values){$values = array();}

        if (empty($nameOrModel)){
            throw new Exception\TemplateException('Invalid template name provided.');
        }

        $output = $this->__environment->render($nameOrModel,$values);
        return $this->getFilterChain()->filter($output);
    }


    #GETTERS AND SETTERS
    /**
     * Return the template engine object, if any
     *
     * @return \ZeTwig\View\Renderer
     */
    public function getEngine()
    {
        return $this;
    }

    /**
     * Get plugin broker instance
     *
     * @return Zend\Loader\Broker
     */
    public function getBroker()
    {
        $this->__environment->getBroker();
    }

    /**
     * Set plugin broker instance
     *
     * @param  string|Broker $broker Plugin broker to load plugins
     * @return Zend\Loader\Pluggable
     */
    public function setBroker($broker)
    {
        $this->__environment->setBroker($broker);
        $this->getBroker()->setView($this);
        return $this;
    }

    /**
     * Get plugin instance
     *
     * @param  string     $name  Name of plugin to return
     * @param  null|array $options Options to pass to plugin constructor (if not already instantiated)
     * @return mixed
     */
    public function plugin($name, array $options = null)
    {
        return $this->__environment->plugin($name, $options);
    }

    /**
     * Set filter chain
     *
     * @param \Zend\Filter\FilterChain $filters
     * @return Renderer
     */
    public function setFilterChain(FilterChain $filters)
    {
        $this->__filterChain = $filters;
        return $this;
    }

    /**
     * Retrieve filter chain for post-filtering script content
     *
     * @return FilterChain
     */
    public function getFilterChain()
    {
        if (null === $this->__filterChain) {
            $this->setFilterChain(new FilterChain());
        }
        return $this->__filterChain;
    }



    /**
     * Set flag indicating whether or not we should render trees of view models
     *
     * If set to true, the View instance will not attempt to render children
     * separately, but instead pass the root view model directly to the PhpRenderer.
     * It is then up to the developer to render the children from within the
     * view script.
     *
     * @param  bool $renderTrees
     * @return PhpRenderer
     */
    public function setCanRenderTrees($renderTrees)
    {
        $this->__renderTrees = (bool) $renderTrees;
        return $this;
    }

    /**
     * Can we render trees, or are we configured to do so?
     *
     * @return bool
     */
    public function canRenderTrees()
    {
        return $this->__renderTrees;
    }
}
