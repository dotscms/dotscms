<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack'   => array(
            'core'              => __DIR__ . '/../views',
        ),
        'template_map' => array(
            'layouts/layout'    => __DIR__ . '/../views/layouts/layout.twig',
            'core/index/index'       => __DIR__ . '/../views/core/index/index.twig',
        ),
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'Core\Controller\Index' => 'Core\Controller\IndexController'
        ),
    ),
//    'controller' => array(
//        'classes' => array(
//            'core-index' => 'Core\Controller\IndexController',
//        ),
//    ),

    //Router Service
    'router' => array(
        'routes' => array(
            'default' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/[:controller[/:action][/]]',
                    'constraints' => array(
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Core\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Core\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

);
