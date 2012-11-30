<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-image-block' => __DIR__ . '/../views',
        ),
    ),

    'zendexperts_zedb' => array(
        'models' => array(
            'DotsImageBlock\Db\Model\ImageBlock' => array(
                'tableName' => 'block_image',
                'entityClass' => 'DotsImageBlock\Db\Entity\ImageBlock',
            ),
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
                        'dots-image-block'      => 'assets/dots-image-block/css/style.css',
                    ),
                ),
                'admin.head.pre' => array(
                    'links' => array(
                        'imgareaselect-default' => 'assets/dots-image-block/lib/img_crop/css/imgareaselect-default.css',
                    ),
                    'scripts' => array(
                        'jquery.imgareaselect'  => 'assets/dots-image-block/lib/img_crop/scripts/jquery.imgareaselect.js',
                        'dots-image-block'      => 'assets/dots-image-block/js/admin.js',
                    ),
                )
            )
        )
    ),
);
