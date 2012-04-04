<?php
namespace ZeTwig\View\Extension\Trigger;

use Twig_Node,
    Twig_Node_Expression as Expression,
    Twig_Compiler as Compiler;

class RenderNode extends Twig_Node
{
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

    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo \$this->env->getExtension('ZeTwig')->triggerEvent(")
            ->subcompile($this->getNode('event'))
            ->raw(', ')
            ->subcompile($this->getNode('target'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(");\n")
        ;
    }


}