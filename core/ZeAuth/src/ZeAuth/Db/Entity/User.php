<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Db\Entity;

use ZeDb\Entity,
    ZeAuth\Db\MapperInterface;

class User extends Entity implements MapperInterface
{
    protected $_data = array(
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
     * @return ZeAuth\Db\MapperInterface
     */
    public function setLastLogin($date = null)
    {
        $this->_data['last_login'] = $date;
        return $this;
    }

}