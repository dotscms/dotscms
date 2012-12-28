<?php
$workingDir = getcwd();
return array(
    'dots'=>array(
        'view'=>array(
            'events'=>array(
                'head.pre'=>array(
                    'scripts'=>array(
                        'html5shiv'         => '//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js',
                        'jquery'        => '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js',
                        'jquery-ui'     => '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js',
                        'bootstrap'     => '//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.2.2/bootstrap.min.js',
                        'underscore'    => '//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min.js',
                        'json2'         => '//cdnjs.cloudflare.com/ajax/libs/json2/20121008/json2.js',
                        'backbone'      => '//cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.2/backbone-min.js',
                    ),
                    'links'=>array(
                        'bootstrap'     => 'assets/dots/lib/bootstrap/css/bootstrap.min.css',
                        'dots'          => 'assets/dots/css/default.css'
                    )
                ),
                'admin.head.pre' => array(
                    'scripts' => array(
                        'jquery.form'   => '//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.20/jquery.form.js',
                        'jquery.json'   => 'assets/dots/lib/jquery.json.js',
                        'dots'          => 'assets/dots/js/dots.js',
                    ),
                    'links' => array(
                        'dots' => 'assets/dots/css/admin.css'
                    )
                )
            )
        )
    ),

    'ze_theme' => array(
        'default_theme' => 'default',
        'theme_paths' => array(
            $workingDir . '/themes/'
        ),
    ),

    'zfctwig' => array(
        'extensions' => array(
            'dots-twig'     => 'DotsTwigExtension'
        ),
        'disable_zf_model'  => false
    ),

    'service_manager' => array(
        'factories' => array(
            'Router' => 'Dots\Mvc\Service\RouterFactory',
        ),
    ),

    // View Manager Service
    'view_manager' => array(
        'display_exceptions' => true,
        'display_not_found_reason' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'layout' => 'layout/layout',
        'template_path_stack' => array(
            'dots' => __DIR__ . '/../views',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'dotsNav' => 'Dots\\View\\Helper\\DotsNav',
            'dotsForm' => 'Dots\\View\\Helper\\DotsForm',
        )
    )

);