<?php
namespace ZeAuth\Db\Model;

use ZeDb\Model,
    ZeAuth\Db\Model as ModelInterface,
    ZeAuth\Db\Mapper,
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
     * @return \ZeAuth\Db\Mapper
     */
    public function getByUsername($identity)
    {
        return $this->_model->getByUsername($identity);
    }

    /**
     * Get the user mapper by email address
     * @param string $identity
     * @return \ZeAuth\Db\Mapper
     */
    public function getByEmailAddress($identity)
    {
        return $this->_model->getByEmail($identity);
    }

    /**
     * Save the user mapper into the database
     * @abstract
     * @param \ZeAuth\Db\Mapper $mapper
     * @return void
     */
    public function save(Mapper $mapper)
    {
        return $this->_model->save($mapper);
    }

    public function __call($name, $args){
        return call_user_func_array(array($this->_model, $name), $args);
    }
}