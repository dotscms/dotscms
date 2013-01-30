<?php
namespace DotsSlideshow;

return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-slideshow' => __DIR__ . '/../views',
        ),
    ),

    'dots_slideshow' => array(
        'image_path'=>'data/uploads/'
    ),

    'zendexperts_zedb' => array(
        'models' => array(
            __NAMESPACE__ . '\Db\Model\SlideshowBlock' => array(
                'tableName' => 'block_slideshows',
                'entityClass' => __NAMESPACE__ . '\Db\Entity\SlideshowBlock',
            ),
            __NAMESPACE__ . '\Db\Model\SlideshowImage' => array(
                'tableName' => 'block_slideshow_images',
                'entityClass' => __NAMESPACE__ . '\Db\Entity\SlideshowImage',
            ),
        ),
    ),

    'dots'=>array(
        'blocks'=>array(
            __NAMESPACE__ . '\Handler\SlideshowHandler',
        ),
        'view' => array(
            'events' => array(
                'head.post' => array(
                    'links' => array(
                        'dots-slideshow' => 'assets/dots-slideshow/css/style.css',
                    ),
                ),
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
                        'jquery.nivo.slider'                => '//cdnjs.cloudflare.com/ajax/libs/jquery-nivoslider/3.1/jquery.nivo.slider.pack.js',
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
            __NAMESPACE__ . '\Controller\Slideshow' => __NAMESPACE__ . '\Controller\SlideshowController'
        ),
    ),

    'ze-auth' => array(
        'restricted_routes' => array(
            'dots-slideshow' => array('dots-slideshow')
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
                        'controller' => __NAMESPACE__ . '\Controller\Slideshow',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
);
