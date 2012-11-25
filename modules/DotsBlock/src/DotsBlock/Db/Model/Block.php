<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsBlock\Db\Model;

use ZeDb\Model;

class Block extends Model
{
    protected $entityClass = '\DotsBlock\Db\Entity\Block';
    protected $tableName = 'blocks';
}