<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsLinkBlock\Db\Entity;

use ZeDb\Entity;

class LinkBlock extends Entity
{
    protected $_data = array(
        'id'                => null, // id of the image content
        'block_id'          => null, // id of the block
        'parent_id'         => null, // id of the parent link
        'title'             => null, // displayed text for the link
        'type'              => null, // type of the link
        'entity_id'         => null, // entity id of the page
        'href'              => null, // location of the link
        'position'          => null, // the position within the
    );
}