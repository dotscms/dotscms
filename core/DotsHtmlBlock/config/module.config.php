<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots-html-block' => __DIR__ . '/../views',
        ),
    ),
    'dots'=>array(
        'blocks'=>array(
            'DotsHtmlBlock\Handler\HtmlHandler',
        ),
        'view' => array(
            'events' => array(
                'admin.head.pre' => array(
                    'scripts' => array(
                        'tiny_mce'                      => 'assets/dots-html-block/lib/tiny_mce/tiny_mce.js',
                        'jquery.tiny_mce'               => 'assets/dots-html-block/lib/tiny_mce/jquery.tinymce.js',
                        'tiny_mce.default_settings'     => 'assets/dots-html-block/lib/tiny_mce/default_settings.js',
                        'dots-html-block'                => 'assets/dots-html-block/js/admin.js',
                    ),
                )
            )
        )
    ),
);