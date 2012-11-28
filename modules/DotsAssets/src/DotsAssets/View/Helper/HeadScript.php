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
use Zend\View\Helper\HeadScript as BaseHeadScript;

class HeadScript extends BaseHeadScript
{
    public function __invoke($mode = HeadScript::FILE, $spec = null, $placement = 'APPEND', array $attrs = array(), $type = 'text/javascript')
    {
        return parent::__invoke($mode, $spec, $placement, $attrs, $type);
    }
}