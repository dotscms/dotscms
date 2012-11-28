<?php
namespace DotsLinkBlock;

return array(

    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-link-block' => __DIR__ . '/../views',
        ),
    ),
    'dots'=>array(
        'blocks'=>array(
            __NAMESPACE__ . '\Handler\LinksHandler',
        ),
        'view' => array(
            'events' => array(
                'head.post' => array(
                    'links' => array(
                        'dots-link-block'   => 'assets/dots-link-block/css/style.css',
                    ),
                ),
                'admin.head.pre' => array(
                    'scripts' => array(
                        'dots-link-block'   => 'assets/dots-link-block/js/admin.js',
                    ),
                )
            )
        )
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\LinkController' => __NAMESPACE__ . '\Controller\LinkController',
        ),
    ),

    //Router Service
    'router' => array(
        'routes' => array(
            'dots-link-block' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots/link-block[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\LinkController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
