<?php
namespace ZeTwig\View\Extension\Render;

use Twig_Node,
    Twig_Node_Expression as Expression,
    Twig_Compiler as Compiler;

class RenderNode extends Twig_Node
{
    public function __construct(Expression $expr, Expression $attributes, Expression $options, $lineno, $tag = null)
    {
        parent::__construct(
            array(
                'expr' => $expr,
                'attributes' => $attributes,
                'options' => $options
            ),
            array(), $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo \$this->env->getExtension('ZeTwig')->renderAction(")
            ->subcompile($this->getNode('expr'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(', ')
            ->subcompile($this->getNode('options'))
            ->raw(");\n")
        ;
    }


}