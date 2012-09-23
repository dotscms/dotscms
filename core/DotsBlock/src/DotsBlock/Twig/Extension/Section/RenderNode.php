<?php
namespace DotsBlock\Twig\Extension\Section;

use Twig_Node,
    Twig_Node_Expression as Expression,
    Twig_Compiler as Compiler;

class RenderNode extends Twig_Node
{
    public function __construct(Expression $name, Expression $target, Expression $attributes, $lineno, $tag = null)
    {
        parent::__construct(
            array(
                'name' => $name,
                'target' => $target,
                'attributes' => $attributes
            ),
            array(), $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write("echo \$this->env->getExtension('DotsBlock')->renderSection(")
            ->subcompile($this->getNode('name'))
            ->raw(', ')
            ->subcompile($this->getNode('target'))
            ->raw(', ')
            ->subcompile($this->getNode('attributes'))
            ->raw(");\n");
    }

}