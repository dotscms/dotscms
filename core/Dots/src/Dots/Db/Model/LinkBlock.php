<?php
namespace Dots\Db\Model;

use ZeDb\Model;

class LinkBlock extends Model
{
    protected $entityClass = '\Dots\Db\Entity\LinkBlock';
    protected $tableName = 'block_links';
}