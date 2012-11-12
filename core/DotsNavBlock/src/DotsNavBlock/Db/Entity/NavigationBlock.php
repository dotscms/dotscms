<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsNavBlock\Db\Entity;

use ZeDb\Entity;

class NavigationBlock extends Entity
{
    protected $_data = array(
        'id'                => null, // id of the image content
        'block_id'          => null, // id of the block
        'parent_id'         => null, // id of the parent navigation
        'title'             => null, // displayed text for the navigation
        'type'              => null, // type of the navigation: page, link, header, -
        'entity_id'         => null, // the id of the page
        'href'              => null, // location of the navigation
        'position'          => null, // the position within the
    );
}