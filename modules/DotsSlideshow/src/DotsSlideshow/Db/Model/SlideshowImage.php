<?php
namespace DotsSlideshow\Db\Model;

use ZeDb\Model;

class SlideshowImage extends Model
{
    protected $entityClass = '\DotsSlideshow\Db\Entity\SlideshowImage';
    protected $tableName = 'block_slideshow_images';
}

