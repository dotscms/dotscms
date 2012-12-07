<?php
$workingDir = getcwd();
return array(
    'dots'=>array(
        'view'=>array(
            'events'=>array(
                'head.pre'=>array(
                    'scripts'=>array(
                        'html5'         => 'assets/dots/lib/html5.js',
                        'jquery'        => 'assets/dots/lib/jquery.min.js',
                        'jquery-ui'     => 'assets/dots/lib/jquery-ui/js/jquery-ui.min.js',
                        'bootstrap'     => 'assets/dots/lib/bootstrap/js/bootstrap.min.js',
                        'underscore'    => 'assets/dots/lib/underscore.min.js',
                        'json2'         => 'assets/dots/lib/json2.js',
                        'backbone'      => 'assets/dots/lib/backbone.min.js',
                    ),
                    'links'=>array(
                        'bootstrap'     => 'assets/dots/lib/bootstrap/css/bootstrap.min.css',
                        'dots'          => 'assets/dots/css/default.css'
                    )
                ),
                'admin.head.pre' => array(
                    'scripts' => array(
                        'jquery.form'   => 'assets/dots/lib/jquery.form.js',
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

    'zendexperts_zetwig' => array(
        'environment_options' => array(
            'cache' => $workingDir . '/data/cache/twig',
        ),
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