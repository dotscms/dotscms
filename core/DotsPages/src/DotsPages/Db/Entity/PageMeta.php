<?php
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