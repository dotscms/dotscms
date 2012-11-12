<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsAssets\View\Helper;
use Zend\View\Helper\HeadLink as BaseHeadLink;

class HeadLink extends BaseHeadLink
{
    public function __invoke(array $attributes = null, $placement = Placeholder\Container\AbstractContainer::APPEND)
    {
        return parent::__invoke($attributes, $placement);
    }
}