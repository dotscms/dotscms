<?php
namespace DotsBlock\Db\Model;

use ZeDb\Model;

class Block extends Model
{
    protected $entityClass = '\DotsBlock\Db\Entity\Block';
    protected $tableName = 'blocks';
}