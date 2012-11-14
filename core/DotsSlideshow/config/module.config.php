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
                        'jquery.ui.widget'                  => 'assets/dots-slideshow/lib/file_upload/js/vendor/jquery.ui.widget.js',
                        'jquery.iframe-transport'           => 'assets/dots-slideshow/lib/file_upload/js/jquery.iframe-transport.js',
                        'jquery.fileupload'                 => 'assets/dots-slideshow/lib/file_upload/js/jquery.fileupload.js',
                        'dots-slideshow'                    => 'assets/dots-slideshow/js/slideshow.js',
                    ),
                ),
                'head.pre' => array(
                    'scripts' => array(
                        'jquery.nivo.slider'                => 'assets/dots-slideshow/lib/nivo_slider/jquery.nivo.slider.pack.js',
                        'dots-slider'                       => 'assets/dots-slideshow/js/slider.js'
                    ),
                    'links' => array(
                        'jquery.nivo.slider'                => 'assets/dots-slideshow/lib/nivo_slider/nivo-slider.css',
                        'jquery.nivo.slider.bar'            => 'assets/dots-slideshow/lib/nivo_slider/themes/bar/bar.css',
                        'jquery.nivo.slider.default'        => 'assets/dots-slideshow/lib/nivo_slider/themes/default/default.css',
                        'jquery.nivo.slider.dark'           => 'assets/dots-slideshow/lib/nivo_slider/themes/dark/dark.css',
                        'jquery.nivo.slider.light'          => 'assets/dots-slideshow/lib/nivo_slider/themes/light/light.css',
                    )
                )
            )
        )
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            'DotsSlideshow\Controller\Slideshow' => 'DotsSlideshow\Controller\SlideshowController'
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
