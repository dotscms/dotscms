<?php
namespace Dots\Db\Entity;

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