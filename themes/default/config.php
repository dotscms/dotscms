<?php
return array(
    'template_path_stack' => array(
        'default'                   => __DIR__ . '/view',
    ),
    'template_map' => array(
        'layout/layout'             => __DIR__ . '/view/layout/layout.twig',
        'layout/main'               => __DIR__ . '/view/layout/main.twig',
        'layout/html'               => __DIR__ . '/view/layout/html.twig',
        'core/index/index'          => __DIR__ . '/view/core/index/index.twig',
        'core/index/display-this'   => __DIR__ . '/view/core/index/display-this.twig',
        'core/index/not-found'      => __DIR__ . '/view/core/index/not-found.twig',
        'error/404'                 => __DIR__ . '/view/error/404.twig',
        'error/index'               => __DIR__ . '/view/error/index.twig',
    ),
);