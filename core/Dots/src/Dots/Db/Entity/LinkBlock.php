<?php
namespace Dots\Db\Entity;

use ZeDb\Entity;

class LinkBlock extends Entity
{
    protected $_data = array(
        'id'                => null, // id of the image content
        'block_id'          => null, // id of the block
        'parent_id'         => null, // id of the parent link
        'type'              => null, // type of the link
        'entity_id'         => null, // type of the link
        'title'             => null, // displayed text for the link
        'href'              => null, // location of the link
        'position'          => null, // the position within the
    );
}