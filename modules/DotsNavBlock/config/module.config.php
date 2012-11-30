<?php
namespace DotsNavBlock;

return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-nav-block' => __DIR__ . '/../views',
        ),
    ),

    'zendexperts_zedb' => array(
        'models' => array(
            'DotsNavBlock\Db\Model\NavigationBlock' => array(
                'tableName' => 'block_navigation',
                'entityClass' => 'DotsNavBlock\Db\Entity\NavigationBlock',
            ),
        ),
    ),

    'dots'=>array(
        'blocks'=>array(
            __NAMESPACE__ . '\Handler\NavigationHandler'
        ),
        'view' => array(
            'events' => array(
                'head.post' => array(
                    'links' => array(
                        'dots-nav-block' => 'assets/dots-nav-block/css/style.css',
                    ),
                ),
                'admin.head.pre' => array(
                    'scripts' => array(
                        'dots-nav-block'    => 'assets/dots-nav-block/js/admin.js',
                    ),
                    'links' => array(
                        'dots-nav-block'    => 'assets/dots-nav-block/css/style.css',
                    )
                )
            )
        )
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\NavigationController' => __NAMESPACE__ . '\Controller\NavigationController',
        ),
    ),

    //Router Service
    'router' => array(
        'routes' => array(
            'dots-nav-block' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots/nav-block[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\NavigationController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
