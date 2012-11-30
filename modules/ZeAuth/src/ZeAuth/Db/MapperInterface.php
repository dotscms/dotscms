<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Db;

use ZeDb\EntityInterface;
/**
 * ZeAuth User Mapper Interface
 * Abstracts the user objects to allow any type of database abstraction layer
 */
interface MapperInterface extends EntityInterface
{
    /**
     * Get the encrypted password for the current user
     * @abstract
     * @return string
     */
    public function getPassword();

    /**
     * Get the password salt field
     * @abstract
     * @return string
     */
    public function getPasswordSalt();

    /**
     * Set the last login field to a specific date or to now
     * @abstract
     * @param string|null $date
     * @return ZeAuth\Db\Mapper
     */
    public function setLastLogin($date = null);
}