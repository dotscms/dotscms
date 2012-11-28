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

class PageMeta extends Entity
{
    protected $_data = array(
        'id'            => null,
        'page_id'       => null,
        'title'         => null,
        'keywords'      => '',
        'description'   => '',
        'author'        => null,
        'robots'        => null,
        'copyright'     => null,
        'charset'       => 'UTF-8',
        'expires_after' => null,
    );
}