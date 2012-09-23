<?php
namespace DotsBlock\Db\Entity;

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