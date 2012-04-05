<?php
namespace Core\Db\Model;

use ZeDb\Model;

class User extends Model
{
    protected $tableName = 'users';
    protected $entityClass = 'Core\Db\Entity\User';
}