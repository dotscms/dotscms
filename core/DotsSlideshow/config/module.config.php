<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-slideshow' => __DIR__ . '/../views',
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
            'DotsSlideshow\Handler\SlideshowHandler',
        ),
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'DotsSlideshow\Handler\SlideshowHandler' => 'DotsSlideshow\Handler\SlideshowHandler',
        ),
    ),

    //Router Service
    'router' => array(
        'routes' => array(
            'dots-slideshow' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dots/slideshow[/:action][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'DotsSlideshow\Controller\Slideshow',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
