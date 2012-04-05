<?php
namespace Core\Db\Entity;

use ZeDb\Entity,
    ZeAuth\Db\Mapper as AuthMapper;

class User extends Entity implements AuthMapper
{
    protected $_data=array(
        'id'            => null,
        'username'      => '',
        'email'         => '',
        'password'      => '',
        'password_salt' => '',
        'last_login'    => null,
        'role'          => 'guest',
    );

    /**
     * Get the encrypted password for the current user
     * @abstract
     * @return string
     */
    public function getPassword(){
        return $this->_data['password'];
    }

    /**
     * Get the password salt field
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->_data['password_salt'];
    }

    /**
     * Set the last login field to a specific date or to now
     * @param string|null $date
     * @return ZeAuth\Db\Mapper
     */
    public function setLastLogin($date)
    {
        $this->_data['last_login'] = $date;
        return $this;
    }

}