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

use Twig_Function;

/**
 * ZeTwig helper view function
 * @package ZeTwig
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
class HelperFunction extends Twig_Function
{
    protected $_name = null;

    public function __construct($name, $options=array())
    {
        parent::__construct($options);
        $this->_name = $name;
    }

    /**
     * Compiles a function.
     *
     * @return string The PHP code for the function
     */
    function compile()
    {
        $name = preg_replace('#[^a-z0-9]+#i', '', $this->_name);
        return '$this->getEnvironment()->plugin("' . $name . '")->__invoke';
    }
}
