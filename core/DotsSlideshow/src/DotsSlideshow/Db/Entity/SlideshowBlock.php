<?php
namespace DotsSlideshow\Db\Entity;

use ZeDb\Entity;

class SlideshowBlock extends Entity
{
    protected $_data = array(
        'id'            => null,
        'block_id'      => null,
        'effect' => 'random',
        'animSpeed' => '500',
        'pauseTime' => '3000',
        'theme' => 'default'
    );
}
