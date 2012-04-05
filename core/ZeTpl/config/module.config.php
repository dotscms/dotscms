<?php
return array(
    'di' => array(
        'instance' => array(
            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'layouts/layout'             => __DIR__ . '/../templates/default/views/layouts/layout.twig',
                        'layouts/main'             => __DIR__ . '/../templates/default/views/layouts/main.twig',
                        'core-index/display-this'   => __DIR__ . '/../templates/default/views/core-index/display-this.twig',
                        'core-index/index'          => __DIR__ . '/../templates/default/views/core-index/index.twig',
                        'core-index/not-found'      => __DIR__ . '/../templates/default/views/core-index/not-found.twig',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'ZeTpl'               => __DIR__ . '/../templates/default/views',
                    ),
                ),
            ),
        ),
    ),
);
