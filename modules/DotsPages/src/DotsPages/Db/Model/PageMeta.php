<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsPages\Db\Model;

use ZeDb\Model;

class PageMeta extends Model
{
    protected $entityClass = '\DotsPages\Db\Entity\PageMeta';
    protected $tableName = 'page_metas';
}