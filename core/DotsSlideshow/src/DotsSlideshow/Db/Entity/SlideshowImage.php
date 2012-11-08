<?php
namespace DotsSlideshow\Db\Entity;

use ZeDB\Entity;

class SlideshowImage extends Entity
{
    protected $_data = array(
        'id'            => null,
        'block_slideshow_id'      => null
    );
}
