<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'core-index' => 'Core\Controller\IndexController',
            ),

            /**
             * Template files and path to default template folder
             */
            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(
                        'layouts/layout' => __DIR__ . '/../views/layouts/layout.twig',
                        'index/index' => __DIR__ . '/../views/core-index/index.twig',
                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'core' => __DIR__ . '/../views',
                    ),
                ),
            ),

            /**
             * Database entities and models
             */
            'Core\Db\Model\User' => array(
                'parameters' => array(
                    'options' => array(
                        'tableName' => 'users',
                        'entityClass' => 'Core\Db\Entity\User',
                    ),
                ),
            ),
            'ZeDb\Registry' => array(
                'parameters' => array(
                    'models' => array(
                        'Core\Db\Entity\User' => 'Core\Db\Model\User'
                    ),
                ),
            ),

            /**
             * Default core routes
             */
            'Zend\Mvc\Router\RouteStack' => array(
                'parameters' => array(
                    'routes' => array(
                        'default' => array(
                            'type'    => 'Zend\Mvc\Router\Http\Segment',
                            'options' => array(
                                'route'    => '/[:controller[/:action]]',
                                'constraints' => array(
                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                ),
                                'defaults' => array(
                                    'controller' => 'core-index',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                        'home' => array(
                            'type' => 'Zend\Mvc\Router\Http\Literal',
                            'options' => array(
                                'route'    => '/',
                                'defaults' => array(
                                    'controller' => 'core-index',
                                    'action'     => 'index',
                                ),
                            ),
                        ),
                    ),
                ),
            ),

        ),
    ),
);
