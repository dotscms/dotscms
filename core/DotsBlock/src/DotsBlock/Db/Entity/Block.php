<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsBlock\Db\Entity;

use ZeDb\Entity;

class Block extends Entity
{
    protected $_data = array(
        'id'                => null,
        'page_id'           => null,
        'section'           => null,
        'type'              => null,
        'position'          => 1,
        'entry_date'        => null,
        'class'             => ''
    );
}