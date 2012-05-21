<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'dots-pages-admin'  =>'DotsPages\Controller\AdminController',
                'dots-pages-page'   =>'DotsPages\Controller\PageController',
            ),

            /**
             * Template files and path to default template folder
             */
            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'dots-pages-admin/add'=> __DIR__ . '/../views/dots-pages/add.twig',
                        'dots-pages-admin/edit'=> __DIR__ . '/../views/dots-pages/edit.twig',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'dots-pages' => __DIR__ . '/../views',
                    ),
                ),
            ),

            'Dots\View\TemplateContainer' => array(
                'parameters' => array(
                    'options' => array(
                        'templates' => array(
                            'default-page' => array(
                                'name' => 'Default Template',
                                'path' => 'dots-pages/pages/page'
                            )
                        )
                    )
                )
            ),

            /**
             * Routes
             */
            'Zend\Mvc\Router\RouteStackInterface' => array(
                'parameters' => array(
                    'routes' => array(
                        'dots-admin-page' => array(
                            'type' => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route' => '/dots-pages[/:action][/]',
                                'constraints' => array(
                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                    'controller' => 'dots-pages-admin',
                                    'action' => 'index',
                                ),
                            ),
                        ),
                        'dots-page' => array(
                            'type' => 'DotsPages\Router\Page',
                            'options'=>array(
                                'defaults' => array(
                                    'controller' => 'dots-pages-page',
                                    'action' => 'view',
                                ),
                            )
                        ),
                        'home' => array(
                            'type' => 'DotsPages\Router\Page',
                            'options' => array(
                                'defaults' => array(
                                    'controller' => 'dots-pages-page',
                                    'action' => 'view',
                                ),
                            )
                        ),
                    ),
                ),
            ),

        ),
    ),
);
