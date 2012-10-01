<?php
namespace DotsPages\Db\Model;

use ZeDb\Model;

class PageMeta extends Model
{
    protected $entityClass = '\DotsPages\Db\Entity\PageMeta';
    protected $tableName = 'page_metas';
}