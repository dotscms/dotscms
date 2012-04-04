<?php
namespace ZeAuth\Db;

interface Model
{
    /**
     * Get the user mapper by username
     * @abstract
     * @param string $identity
     * @return \ZeAuth\Db\Mapper
     */
    public function getByUsername($identity);

    /**
     * Get the user mapper by email address
     * @abstract
     * @param string $identity
     * @return \ZeAuth\Db\Mapper
     */
    public function getByEmailAddress($identity);
    
    /**
     * Save the user mapper into the database
     * @abstract
     * @param \ZeAuth\Db\Mapper $mapper
     * @return void
     */
    public function save(\ZeAuth\Db\Mapper $mapper);
}