<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth;

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
            case 'plain';
                return $this->plain($password);
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

    public function plain($password=''){
        return $password;
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