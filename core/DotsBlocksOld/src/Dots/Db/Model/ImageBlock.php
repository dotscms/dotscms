<?php
namespace Dots\Db\Model;

use ZeDb\Model;

class ImageBlock extends Model
{
    protected $entityClass = '\Dots\Db\Entity\ImageBlock';
    protected $tableName = 'block_image';
}