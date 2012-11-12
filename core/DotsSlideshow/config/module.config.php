<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-slideshow' => __DIR__ . '/../views',
        ),
    ),
    'dots'=>array(
        'blocks'=>array(
            'DotsSlideshow\Handler\SlideshowHandler',
        ),
        'view' => array(
            'events' => array(
                'admin.head.pre' => array(
                    'scripts' => array(
                        'jquery.ui.widget'                  => '/assets/file_upload/js/vendor/jquery.ui.widget.js',
                        'jquery.iframe-transport'           => '/assets/file_upload/js/jquery.iframe-transport.js',
                        'jquery.fileupload' => '/assets/file_upload/js/jquery.fileupload.js',
                        'dots-slideshow'                => '/assets/dots_slideshow/slideshow.js',
                    ),
                ),
                'head.pre' => array(
                    'scripts' => array(
                        'jquery.nivo.slider' => '/assets/nivo_slider/jquery.nivo.slider.pack.js',
                        'dots-slider' => '/assets/dots_slideshow/slider.js'
                    ),
                    'links' => array(
                        'jquery.nivo.slider' => '/assets/nivo_slider/nivo-slider.css',
                        'jquery.nivo.slider.bar' => '/assets/nivo_slider/themes/bar/bar.css',
                        'jquery.nivo.slider.default' => '/assets/nivo_slider/themes/default/default.css',
                        'jquery.nivo.slider.dark' => '/assets/nivo_slider/themes/dark/dark.css',
                        'jquery.nivo.slider.light' => '/assets/nivo_slider/themes/light/light.css',
                    )
                )
            )
        )
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'DotsSlideshow\Controller\SlideshowController' => 'DotsSlideshow\Controller\SlideshowController'
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
                        'controller' => 'DotsSlideshow\Controller\SlideshowController',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
