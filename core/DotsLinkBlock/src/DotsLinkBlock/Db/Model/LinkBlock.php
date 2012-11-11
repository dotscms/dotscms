<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsLinkBlock\Db\Model;

use ZeDb\Model;

class LinkBlock extends Model
{
    protected $entityClass = '\DotsLinkBlock\Db\Entity\LinkBlock';
    protected $tableName = 'block_links';
}