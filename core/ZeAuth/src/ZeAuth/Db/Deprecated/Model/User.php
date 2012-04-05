<?php
namespace ZeAuth\Db\Model;

use ZeAuth\Db,
    ZeAuth\Module;

class User implements Db\Model
{

    /**
     * Get the user mapper by username
     * @param string $identity
     * @return \ZeAuth\Db\Mapper
     */
    public function getByUsername($identity)
    {
        $db = Module::locator()->get('ze-auth-db');
        $sql = $db->select();
        $sql->from('users')
            ->where('username = ?', $identity)
            ->limit(1);
        $data = $db->fetchRow($sql);
        if (!$data){
            return false;
        }
        $mapper = new Db\Mapper\User(array('data'=>$data));
        return $mapper;
    }
    
    /**
     * Get the user mapper by email address
     * @param string $identity
     * @return ZeAuth\Db\Mapper
     */
    public function getByEmailAddress($identity)
    {
        $db = Module::locator()->get('ze-auth-db');
        $sql = $db->select();
        $sql->from('users')
            ->where('email_address = ?', $identity)
            ->limit(1);
        $data = $db->fetchRow($sql);
        if (!$data){
            return false;
        }
        $mapper = new Db\Mapper\User(array('data'=>$data));
        return $mapper;
    }
    
    /**
     * Save the user mapper into the database
     * @param \ZeAuth\Db\Mapper $mapper
     * @return void
     */
    public function save(\ZeAuth\Db\Mapper $mapper)
    {
        $db = Module::locator()->get('ze-auth-db');
        $db->save('users', $mapper->toArray());
    }

}