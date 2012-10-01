<?php
namespace DotsBlock\Db\Model;

use ZeDb\Model;

class ImageBlock extends Model
{
    protected $entityClass = '\DotsBlock\Db\Entity\ImageBlock';
    protected $tableName = 'block_image';
}