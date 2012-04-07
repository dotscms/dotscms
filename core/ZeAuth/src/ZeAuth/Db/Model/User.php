<?php
namespace ZeAuth\Db\Model;

use ZeDb\Model;

class User extends Model
{
    protected $tableName = 'users';
    protected $entityClass = 'ZeAuth\Db\Entity\User';
}