<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsHtmlBlock\Db\Entity;

use ZeDb\Entity;

class HtmlBlock extends Entity
{
    protected $_data = array(
        'id'            => null,
        'block_id'      => null,
        'content'       => null
    );
}