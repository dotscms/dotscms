<?php
/**
 * @author Cosmin Harangus <cosmin@zendexperts.com>
 */
return array(
    'ze-auth' => array(
        'restricted_routes' => array(),
        'home_route' => 'dots-page',
    ),

    //Router Service
    'router' => array(
        'routes' => array(
            'ze-auth' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/auth/',
                    'defaults' => array(
                        'controller' => 'ZeAuth\Controller\Auth',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'logout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'logout/',
                            'defaults' => array(
                                'controller' => 'ZeAuth\Controller\Auth',
                                'action' => 'logout',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

);