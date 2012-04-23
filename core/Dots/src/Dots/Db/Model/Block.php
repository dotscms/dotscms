<?php
namespace Dots\Db\Model;

use ZeDb\Model;

class Block extends Model
{
    protected $entityClass = '\Dots\Db\Entity\Block';
    protected $tableName = 'blocks';
}