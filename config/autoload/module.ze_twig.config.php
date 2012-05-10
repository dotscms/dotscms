<?php
return array(
    'di' => array(
        'instance' => array(
            // View for the layout
            'Zend\Mvc\View\DefaultRenderingStrategy' => array(
                'parameters' => array(
                    'layoutTemplate' => 'layouts/layout',
                ),
            ),
            'ZeTwig\View\Environment'=>array(
                'injections' => array(
                    'ZeTwig\View\Extension',
                    'Dots\Block\Extension'
                ),
                'parameters' => array(
                    'broker' => 'Zend\View\HelperBroker',
                    'options' => array(
                        'cache' => BASE_PATH . '/data/cache/twig',
                        'auto_reload' => true,
                        'debug' => true
                    ),
                ),
            ),

            // Injecting the router into the url helper
            'Zend\View\Helper\Url' => array(
                'parameters' => array(
                    'router' => 'Zend\Mvc\Router\RouteStackInterface',
                ),
            ),
            // Configuration for the doctype helper
            'Zend\View\Helper\Doctype' => array(
                'parameters' => array(
                    'doctype' => 'HTML5',
                ),
            ),
            // View script rendered in case of 404 exception
            'Zend\Mvc\View\RouteNotFoundStrategy' => array(
                'parameters' => array(
                    'displayNotFoundReason' => true,
                    'displayExceptions' => true,
                    'notFoundTemplate' => 'error/404',
                ),
            ),
            // View script rendered in case of other exceptions
            'Zend\Mvc\View\ExceptionStrategy' => array(
                'parameters' => array(
                    'displayExceptions' => true,
                    'exceptionTemplate' => 'error/index',
                ),
            ),

        ),
    ),
);
