<?php
namespace Dots\Db\Model;

use ZeDb\Model;

class HtmlBlock extends Model
{
    protected $entityClass = '\Dots\Db\Entity\HtmlBlock';
    protected $tableName = 'block_html';
}