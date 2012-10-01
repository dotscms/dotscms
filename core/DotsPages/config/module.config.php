<?php
return array(
    // Dots Page templates
    'dots-pages' => array(
        'templates' => array(
            'default-page' => array(
                'name' => 'Default Template',
                'path' => 'dots-pages/pages/page'
            )
        )
    ),

    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-pages'    => __DIR__ . '/../views',
        ),
        'template_map' => array(
            'dots-pages/admin/add'  => __DIR__ . '/../views/dots-pages/admin/add.twig',
            'dots-pages/admin/edit' => __DIR__ . '/../views/dots-pages/admin/edit.twig',
        ),
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'DotsPages\Controller\Admin' => 'DotsPages\Controller\AdminController',
            'DotsPages\Controller\Page' => 'DotsPages\Controller\PageController',
        ),
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
                'type' => 'DotsPages\Router\Page',
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
