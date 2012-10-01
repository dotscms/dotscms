<?php
namespace DotsPages\Db\Model;

use ZeDb\Model;

class Page extends Model
{
    protected $entityClass = '\DotsPages\Db\Entity\Page';
    protected $tableName = 'pages';
}