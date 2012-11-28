<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\Twig;
use Twig_Extension;
use Zend\ServiceManager\ServiceLocatorInterface;
use Dots\Twig\TokenParser\Trigger;
//use Dots\Twig\TokenParser\Render;

/**
 * Twig Extension Class for rendering actions and triggering events directly from the template files
 *
 * @author Cosmin Harangus <cosmin@dotscms.com>
 */
class Extension extends Twig_Extension
{
    protected $serviceLocator = null;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function get($name)
    {
        return $this->serviceLocator->get($name);
    }

    public function getTokenParsers()
    {
        return array(
            new Trigger(),
//            new Render(),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'DotsTwig';
    }

}
