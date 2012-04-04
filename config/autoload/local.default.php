<?php
/**
 * Local Configuration Template File
 *
 * You can use this file for overridding configuration values from modules, etc.  
 * You would place values in here that are agnostic to the environment and not 
 * sensitive to security. 
 *
 * @NOTE: Rename this file to local.config.php and then edit the default values or add your own
 */

return array(
    'di' => array(
        'definition'=>'',
        'instance'=>array(
            'Zend\Db\Adapter\PdoMysql' => array(
                'parameters' => array(
                    'config' => array(
                        'host' => 'localhost',
                        'username' => 'root',
                        'password' => '',
                        'dbname' => 'projectquery',
                    ),
                ),
            ),
        )
    ),
);
