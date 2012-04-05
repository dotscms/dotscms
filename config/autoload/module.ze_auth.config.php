<?php
/**
 * @author: Cosmin Harangus <cosmin@around25.com>
 * Date: 2012-01-09
 * Time: 11:28 PM
 */
return array(
    'ze-auth' => array(
        'restricted_routes' => array(),
    ),

    #Auth Routing System
    'di'=>array(
        'instance'=>array(
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'ze-auth' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'priority' => 1000,
                            'options' => array(
                                'route' => '/auth/',
                                'defaults' => array(
                                    'controller' => 'ze-auth-auth',
                                    'action'=>'index'
                                ),
                            ),
                            'may_terminate' => true,
                            'child_routes' => array(
                                'logout' => array(
                                    'type' => 'Zend\Mvc\Router\Http\Literal',
                                    'options' => array(
                                        'route' => '/logout/',
                                        'defaults' => array(
                                            'controller' => 'ze-auth-auth',
                                            'action'     => 'logout',
                                        ),
                                    ),
                                ),
//                                'register' => array(
//                                    'type' => 'Zend\Mvc\Router\Http\Literal',
//                                    'options' => array(
//                                        'route' => '/register/',
//                                        'defaults' => array(
//                                            'controller' => 'ze-auth-auth',
//                                            'action'     => 'register',
//                                        ),
//                                    ),
//                                ),
                            ),
                        ),
                    ),
                ),
            ),

        )
    )
);