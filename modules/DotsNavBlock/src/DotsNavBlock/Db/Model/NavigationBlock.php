<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsNavBlock\Db\Model;

use ZeDb\Model;

class NavigationBlock extends Model
{
    protected $entityClass = '\DotsNavBlock\Db\Entity\NavigationBlock';
    protected $tableName = 'block_navigation';
}