<?php
namespace ZeAuth\Db\Mapper;

use ZeAuth\Module,
    ZeAuth\Db;

class User implements Db\Mapper
{
    protected $data = array(
        
    );
    
    public function __construct(array $config)
    {
        if (array_key_exists('data', $config)){
            $this->data = $config['data'];
        }
    }

    /**
     * Get the encrypted password for the current user
     * @return string
     */
    public function getPassword()
    {
        return $this->data['password'];
    }

    /**
     * Get the password salt field
     * @return string
     */
    public function getPasswordSalt()
    {
        return $this->data['password_salt'];
    }

    /**
     * Set the last login field to a specific date or to now
     * @param string|null $date
     * @return ZeAuth\Db\Mapper
     */
    public function setLastLogin($date = null)
    {
        if (!$date){
            $date = date('Y-m-d H:i:s');
        }
        $this->data['last_login'] = $date;
        return $this;
    }

    /**
     * Persist the object into the database
     * @return ZeAuth\Db\Mapper
     */
    public function save()
    {
        $model = Module::locator()->get('ze-auth-model_user');
        $model->save($this);
        return $this;
    }

    /**
     * Return an array representation of the data
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
    
}