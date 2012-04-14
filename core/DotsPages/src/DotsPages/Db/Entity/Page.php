<?php
namespace DotsPages\Db\Entity;

use ZeDb\Entity;

class Page extends Entity
{
    protected $_data = array(
        'id'                => null,
        'alias'             => '',
        'title'             => '',
        'meta_keywords'     => '',
        'meta_description'  => '',
        'template'          => null,
        'language'          => null,
        'create_date'       => null,
        'last_update'       => null,
    );
}