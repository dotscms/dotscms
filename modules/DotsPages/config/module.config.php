<?php
return array(
    'ze-auth' => array(
        'restricted_routes' => array(
            'dots-pages'=>array('dots-admin-page')
        ),
        'home_route' => 'dots-page',
    ),

    'zendexperts_zedb' => array(
        'models' => array(
            'DotsPages\Db\Model\Page' => array(
                'tableName' => 'pages',
                'entityClass' => 'DotsPages\Db\Entity\Page',
            ),
            'DotsPages\Db\Model\PageMeta' => array(
                'tableName' => 'page_metas',
                'entityClass' => 'DotsPages\Db\Entity\PageMeta',
            ),
        ),
    ),

    // Dots Page templates
    'dots-pages' => array(
        'templates' => array(
            'default-page' => array(
                'name' => 'Default Template',
                'path' => 'dots-pages/pages/page'
            )
        )
    ),

    'dots' => array(
        'view' => array(
            'events' => array(
                'admin.head.pre' => array(
                    'scripts' => array(
                        'dots-pages'    => 'assets/dots-pages/js/admin.js'
                    )
                )
            )
        )
    ),

    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-pages'    => __DIR__ . '/../views',
        ),
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'DotsPages\Controller\Admin' => 'DotsPages\Controller\AdminController',
            'DotsPages\Controller\Page' => 'DotsPages\Controller\PageController',
        ),
    ),

    'route_manager'=>array(
        'invokables'=>array(
            'dots-page'=>'DotsPages\\Router\\Page',
        )
    ),

    //Router Service
    'router' => array(
        'routes' => array(
            'dots-admin-page' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots-pages[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'DotsPages\Controller\Admin',
                        'action' => 'index',
                    ),
                ),
            ),

            'dots-page' => array(
                'type' => 'dots-page',
                'options' => array(
                    'defaults' => array(
                        'controller' => 'DotsPages\Controller\Page',
                        'action' => 'view',
                    ),
                )
            ),
        ),
    ),
);
