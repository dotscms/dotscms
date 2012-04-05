<?php
namespace ZeAuth\Db;

/**
 * ZeAuth User Mapper Interface
 * Abstracts the user objects to allow any type of database abstraction layer
 */
interface Mapper
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
    public function setLastLogin($date);

    /**
     * Persist the object into the database
     * @abstract
     * @return ZeAuth\Db\Mapper
     */
    public function save();

    /**
     * Return an array representation of the data
     * @abstract
     * @return array
     */
    public function toArray();
}