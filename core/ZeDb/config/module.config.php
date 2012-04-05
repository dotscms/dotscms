<?php
return array(
    'di'    => array(
        'instance' => array(
            'alias' => array(
                'zedb' => 'ZeDb\Registry'
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
