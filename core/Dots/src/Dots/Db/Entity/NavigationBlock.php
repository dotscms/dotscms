<?php
namespace Dots\Db\Entity;

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