<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-block' => __DIR__ . '/../views',
        ),
    ),
    // View Manager Service
    'zfctwig' => array(
        'extensions' => array(
            'DotsBlock' => 'DotsBlock\Twig\Extension'
        ),
    ),
    'dots'=>array(
        'blocks'=>array(
            'DotsBlock\Handler\HtmlHandler',
            'DotsBlock\Handler\ImageHandler',
            'DotsBlock\Handler\LinksHandler',
            'DotsBlock\Handler\NavigationHandler'
        ),
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'DotsBlock\Controller\Block' => 'DotsBlock\Controller\BlockController',
            'DotsBlock\Handler\LinksHandler' => 'DotsBlock\Handler\LinksHandler',
            'DotsBlock\Handler\NavigationHandler' => 'DotsBlock\Handler\NavigationHandler',
        ),
    ),

    //Router Service
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
            'dots-block-link' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots/link-block[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'DotsBlock\Handler\LinksHandler',
                        'action' => 'index',
                    ),
                ),
            ),
            'dots-block-navigation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots/nav-block[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'DotsBlock\Handler\NavigationHandler',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
