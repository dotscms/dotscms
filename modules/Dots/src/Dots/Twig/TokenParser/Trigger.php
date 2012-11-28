<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\Twig\TokenParser;
use Twig_TokenParser;
use Twig_Token;
use Twig_Node_Expression_Array;
use Twig_Node_Expression_Constant;
use Dots\Twig\Node\Trigger as TriggerNode;
/**
 *
 * @author Cosmin Harangus <cosmin@dotscms.com>
 */
class Trigger extends Twig_TokenParser
{
    /**
     * {@inheritDoc}
     */
    public function parse(Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        // target
        if ($this->parser->getStream()->test(Twig_Token::NAME_TYPE, 'on')) {
            $this->parser->getStream()->next();
            $target = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $target = new Twig_Node_Expression_Constant(null, $token->getLine());
        }

        // attributes
        if ($this->parser->getStream()->test(Twig_Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();
            $attributes = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $attributes = new Twig_Node_Expression_Array(array(), $token->getLine());
        }

        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new TriggerNode($expr, $target, $attributes, $token->getLine(), $this->getTag());
    }

    /**
     * {@inheritDoc}
     */
    public function getTag()
    {
        return 'trigger';
    }

}