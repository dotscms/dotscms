<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DotsImageBlock\Db\Model;

use ZeDb\Model;

class ImageBlock extends Model
{
    protected $entityClass = '\DotsImageBlock\Db\Entity\ImageBlock';
    protected $tableName = 'block_image';
}