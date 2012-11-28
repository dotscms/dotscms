<?php
namespace DotsSlideshow\Db\Entity;

use ZeDb\Entity;

class SlideshowImage extends Entity
{
    protected $_data = array(
        'id'            => null,
        'block_slideshow_id'      => null,
        'src' => "",
        "caption" => "",
        "order" => ""
    );
}
