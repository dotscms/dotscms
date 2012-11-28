<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\Twig\Node;
use Twig_Node;
use Twig_Node_Expression as Expression;
use Twig_Compiler as Compiler;
/**
 *
 * @author Cosmin Harangus <cosmin@dotscms.com>
 */
class Trigger extends Twig_Node
{
    /**
     * {@inheritDoc}
     */
    public function __construct(Expression $event, Expression $target, Expression $attributes, $lineno, $tag = null)
    {
        parent::__construct(
            array(
                'event' => $event,
                'target' => $target,
                'attributes' => $attributes
            ),
            array(), $lineno, $tag);
    }

    /**
     * {@inheritDoc}
     */
    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo \$this->env->getExtension('DotsTwig')->get('DotsTwigTriggerHelper')->__invoke(")
            ->subcompile($this->getNode('event'))
            ->raw(', ')
            ->subcompile($this->getNode('target'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(");\n");
    }
}
