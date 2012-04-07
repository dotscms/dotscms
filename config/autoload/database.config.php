<?php
return array(
    'di' =>array(
        'instance' =>array(

            /**
             * Database registry
             */
            'ZeDb\Registry' => array(
                'parameters' => array(
                    'models' => array(
                        'ZeAuth\Db\Entity\User' => 'ZeAuth\Db\Model\User'
                    ),
                ),
            ),

            /**
             * Database Model configuration
             */
            'ZeAuth\Db\Model\User' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'users',
                        'entityClass' => 'ZeAuth\Db\Entity\User',
                    ),
                ),
            ),


        )
    )
);