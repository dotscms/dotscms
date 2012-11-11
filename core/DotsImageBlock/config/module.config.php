<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-image-block' => __DIR__ . '/../views',
        ),
    ),
    'dots'=>array(
        'blocks'=>array(
            'DotsImageBlock\Handler\ImageHandler',
        ),
        'view' => array(
            'events' => array(
                'head.post' => array(
                    'links' => array(
                        'dots-image-block' => '/assets/dots-image-block/css/style.css',
                    ),
                ),
                'admin.head.pre' => array(
                    'links' => array(
                        'imgareaselect-default' => '/assets/dots-image-block/lib/img_crop/css/imgareaselect-default.css',
//                        'dots-image-block' => '/assets/dots-image-block/css/style.css',
                    ),
                    'scripts' => array(
                        'jquery.imgareaselect'  => '/assets/dots-image-block/lib/img_crop/scripts/jquery.imgareaselect.js',
                        'dots-image-block'      => '/assets/dots-image-block/js/admin.js',
                    ),
                )
            )
        )
    ),
);
