<?php
return array(
    'di'                    => array(
        'instance' => array(
            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'layout/layout' => __DIR__ . '/../views/layouts/layout.twig',
                        'layout/main_one_column' => __DIR__ . '/../views/layouts/main_one_column.twig',
                        'layout/main_two_columns' => __DIR__ . '/../views/layouts/main_two_columns.twig',
//                        'index/index' => __DIR__ . '/../views/index/index.twig',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'default-tpl' => __DIR__ . '/../views',
                    ),
                ),
            ),
        ),
    ),
);
