<?php
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