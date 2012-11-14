<?php
namespace DotsSlideshow\Db\Model;

use ZeDb\Model;

class SlideshowBlock extends Model
{
    protected $entityClass = '\DotsSlideshow\Db\Entity\SlideshowBlock';
    protected $tableName = 'block_slideshows';
}
