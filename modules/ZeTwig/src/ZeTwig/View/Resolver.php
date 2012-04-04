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

use Zend\View\Resolver\AggregateResolver,
    Twig_LoaderInterface as LoaderInterface;

/**
 * ZeTwig Resolver class
 * @package ZeTwig
 * @version 0.2
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class Resolver extends AggregateResolver implements LoaderInterface
{
    /**
     * @var null|array
     */
    protected $_config = null;

    /**
     * Setter for config
     * @param array $config
     * @return Loader
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Gets the source code of a template, given its name.
     *
     * @param  string $name The name of the template to load
     * @return string The template source code
     */
    public function getSource($name)
    {
        $path = $this->resolve($name);
        if (!$path){
            throw new Exception\TemplateException(sprintf('Template "%s" not found.', $name));
        }
        return file_get_contents($path);
    }

    /**
     * Gets the cache key to use for the cache for a given template name.
     *
     * @param  string $name The name of the template to load
     * @return string The cache key
     */
    public function getCacheKey($name)
    {
        $path = $this->resolve($name);
        return $path;
    }

    /**
     * Returns true if the template is still fresh.
     *
     * @param string    $name The template name
     * @param timestamp $time The last modification time of the cached template
     * @return boolean
     */
    public function isFresh($name, $time)
    {
        $path = $this->resolve($name);
        if (!$path){
            return false;
        }
        return filemtime($path) < $time;
    }

}