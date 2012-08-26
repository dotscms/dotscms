<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots' => __DIR__ . '/../views',
        ),
        'helper_map' => array(
            'dots' => 'Dots\Helper\Dots',
        )
    ),

    'zfctwig' => array(
        'extensions' => array(
            'DotsBlock' => 'Dots\Block\Extension'
        ),
    ),
    'dots'=>array(
        'blocks'=>array(
            'Dots\Block\Handler\HtmlHandler',
            'Dots\Block\Handler\ImageHandler',
            'Dots\Block\Handler\LinksHandler',
            'Dots\Block\Handler\NavigationHandler'
        ),
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'Dots\Controller\Block' => 'Dots\Controller\BlockController',
            'Dots\Block\Handler\LinksHandler' => 'Dots\Block\Handler\LinksHandler',
            'Dots\Block\Handler\NavigationHandler' => 'Dots\Block\Handler\NavigationHandler',
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
                        'controller' => 'Dots\Controller\Block',
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
                        'controller' => 'Dots\Block\Handler\LinksHandler',
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
                        'controller' => 'Dots\Block\Handler\NavigationHandler',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),


    'di' => array(
        'instance' => array(
            'alias' => array(
                'dots-templates' => 'Dots\View\TemplateContainer'
            ),

            'Dots\View\TemplateContainer' => array(
                'parameters' => array(
                    'options' => array(
                        'templates' => array(

                        )
                    )
                )
            ),

        ),
    ),
);
