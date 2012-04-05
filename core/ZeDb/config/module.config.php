<?php
return array(
    'di'    => array(
        'instance' => array(
            'alias' => array(
                'zedb' => 'ZeDb\Registry'
            ),
            'Zend\Db\Adapter\Adapter'=>array(
                'parameters'=>array(
                    'driver'=>array(
                        'driver' => 'Pdo_Mysql', //'MySqli',
                        'database' => 'zf2dev',
                        'username' => 'root',
                        'password' => ''
                    )
                )
            ),
            'Core\Db\Model\User'=>array(
                'parameters' => array(
                    'options'=>array(
                        'tableName' => 'users',
                        'entityClass'=> 'Core\Db\Entity\User',
                    ),
                ),
            ),
            'ZeDb\Registry' => array(
                'parameters' => array(
                    'models' => array(
                        'Core\Db\Entity\User' => 'Core\Db\Model\User'
                    ),
                ),
            ),
        ),
    ),
);
