<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-block' => __DIR__ . '/../views',
        ),
    ),
    'zendexperts_zedb' => array(
        'models' => array(
            'DotsBlock\Db\Model\Block' => array(
                'tableName' => 'blocks',
                'entityClass' => 'DotsBlock\Db\Entity\Block',
            ),
        ),
    ),
    // Add DotsBlock extension to ZfcTwig
    'zfctwig' => array(
        'extensions' => array(
            'DotsBlock' => 'DotsBlock\Twig\Extension'
        ),
    ),
    // Set up default blocks, css and js
    'dots'=>array(
        'blocks'=>array(),
        'view' => array(
            'events' => array(
                'head.post' => array(
                    'links' => array(
                        'dots-block' => 'assets/dots-block/css/style.css',
                    ),
                ),
                'admin.head.pre' => array(
                    'scripts' => array(
                        'dots-block' => 'assets/dots-block/js/admin.js',
                    ),
                )
            )
        )
    ),
    // Set up default functionality for managing blocks
    'controllers' => array(
        'invokables' => array(
            'DotsBlock\Controller\Block' => 'DotsBlock\Controller\BlockController',
        ),
    ),
    // Set up default routes for dots blocks management
    'router' => array(
        'routes' => array(
            'dots-block' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots/block[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'DotsBlock\Controller\Block',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
