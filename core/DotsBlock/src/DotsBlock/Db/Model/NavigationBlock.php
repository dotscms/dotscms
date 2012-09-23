<?php
namespace DotsBlock\Db\Model;

use ZeDb\Model;

class NavigationBlock extends Model
{
    protected $entityClass = '\DotsBlock\Db\Entity\NavigationBlock';
    protected $tableName = 'block_navigation';
}