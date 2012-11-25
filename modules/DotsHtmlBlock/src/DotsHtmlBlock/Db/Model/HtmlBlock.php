<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsHtmlBlock\Db\Model;

use ZeDb\Model;

class HtmlBlock extends Model
{
    protected $entityClass = '\DotsHtmlBlock\Db\Entity\HtmlBlock';
    protected $tableName = 'block_html';
}