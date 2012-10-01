<?php
namespace Dots\Db\Model;

use ZeDb\Model;

class NavigationBlock extends Model
{
    protected $entityClass = '\Dots\Db\Entity\NavigationBlock';
    protected $tableName = 'block_navigation';
}