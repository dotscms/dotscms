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

interface ModelInterface
{
    /**
     * Get the user mapper by username
     * @abstract
     * @param string $identity
     * @return \ZeAuth\Db\MapperInterface
     */
    public function getByUsername($identity);

    /**
     * Get the user mapper by email address
     * @abstract
     * @param string $identity
     * @return \ZeAuth\Db\MapperInterface
     */
    public function getByEmailAddress($identity);
    
    /**
     * Save the user mapper into the database
     * @abstract
     * @param \ZeAuth\Db\MapperInterface $mapper
     * @return void
     */
    public function save(\ZeAuth\Db\MapperInterface $mapper);
}