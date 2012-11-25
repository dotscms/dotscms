<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsPages\Db\Entity;

use ZeDb\Entity;

class Page extends Entity
{
    protected $_data = array(
        'id'                => null,
        'alias'             => '',
        'title'             => '',
        'template'          => null,
        'language'          => null,
        'position'          => 1,
        'create_date'       => null,
        'last_update'       => null,
    );
}