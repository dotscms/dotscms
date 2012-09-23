<?php
namespace DotsBlock\Db\Model;

use ZeDb\Model;

class LinkBlock extends Model
{
    protected $entityClass = '\DotsBlock\Db\Entity\LinkBlock';
    protected $tableName = 'block_links';
}