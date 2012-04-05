<?php
namespace ZeAuth;

use ZeAuth\Module;

class Crypt
{
    /**
     * Encode based on specified algorithm
     * @throws Exception
     * @param string $algorithm
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function encode($algorithm='sha1', $password='', $salt='')
    {
        switch($algorithm){
            case 'sha1':
                return $this->sha1($password, $salt);
            case 'md5':
                return $this->md5($password, $salt);
        }
        throw new Exception('Invalid credential encryption algorithm specified');
    }

    /**
     * Encode with sha1
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function sha1($password='', $salt='')
    {
        return sha1($password.$salt);
    }

    /**
     * Encode with md5
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function md5($password='', $salt='')
    {
        return md5($password.$salt);
    }
}