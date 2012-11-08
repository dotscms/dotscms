<?php
return array(
    'dots'=>array(
        'view'=>array(
            'events'=>array(
                'head.pre'=>array(
                    'scripts'=>array(
                        '/assets/default/js/html5.js',
                        '/assets/default/js/jquery.min.js',
                        '/assets/default/js/jquery-ui.min.js',
                        '/assets/bootstrap/js/bootstrap.min.js',
                        '/assets/default/js/underscore.min.js',
                        '/assets/default/js/json2.js',
                        '/assets/default/js/backbone.min.js',
                    )
                ),
                'admin.head.pre' => array(
                    'scripts' => array(
                        '/assets/default/js/jquery.form.js',
                        '/assets/default/js/jquery.json.js',
                        '/assets/dots/js/dots.js',
                    )
                )
            )
        )
    ),
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots' => __DIR__ . '/../views',
        ),
        'template_map' => array(
            'layouts/layout' => __DIR__ . '/../views/layouts/layout.twig',
        ),
        'helper_map' => array(
            'dotsNav' => 'Dots\\View\\Helper\\DotsNav',
            'dotsForm' => 'Dots\\View\\Helper\\DotsForm',
        )
    ),
);
