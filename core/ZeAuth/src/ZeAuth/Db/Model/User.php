<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth\Db\Model;

use ZeDb\Model,
    ZeAuth\Db\ModelInterface as ModelInterface,
    ZeAuth\Db\MapperInterface,
    Zend\Db\Adapter\Adapter;

class User implements ModelInterface
{
    private $_model = null;

    /**
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @param array $options
     */
    public function __construct(Adapter $adapter, $options = null)
    {
        $this->_model = new Model($adapter, $options);
    }

    /**
     * Get the user mapper by username
     * @param string $identity
     * @return \ZeAuth\Db\MapperInterface
     */
    public function getByUsername($identity)
    {
        return $this->_model->getByUsername($identity);
    }

    /**
     * Get the user mapper by email address
     * @param string $identity
     * @return \ZeAuth\Db\MapperInterface
     */
    public function getByEmailAddress($identity)
    {
        return $this->_model->getByEmail($identity);
    }

    /**
     * Save the user mapper into the database
     * @abstract
     * @param \ZeAuth\Db\MapperInterface $mapper
     * @return void
     */
    public function save(MapperInterface $mapper)
    {
        $this->_model->save($mapper);
    }

    public function __call($name, $args){
        return call_user_func_array(array($this->_model, $name), $args);
    }
}