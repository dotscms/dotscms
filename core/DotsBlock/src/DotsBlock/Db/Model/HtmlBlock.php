<?php
namespace DotsBlock\Db\Model;

use ZeDb\Model;

class HtmlBlock extends Model
{
    protected $entityClass = '\DotsBlock\Db\Entity\HtmlBlock';
    protected $tableName = 'block_html';
}