<?php
namespace Core\Db\Entity;

use ZeDb\Entity;

class User extends Entity
{
    protected $_data=array(
        'id'=>null,
        'username'=>'',
        'email'=>'',
        'password'=>'',
        'role'=>'',
    );
}