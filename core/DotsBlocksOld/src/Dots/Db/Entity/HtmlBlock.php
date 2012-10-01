<?php
namespace Dots\Db\Entity;

use ZeDb\Entity;

class HtmlBlock extends Entity
{
    protected $_data = array(
        'id'            => null,
        'block_id'      => null,
        'content'       => null
    );
}