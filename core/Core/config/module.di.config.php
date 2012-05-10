<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Core\\Controller\\IndexController' => array(
                    'setEventManager' => array(
                        'events' => array(
                            'type' => 'Zend\\EventManager\\EventManagerInterface',
                            'required' => true,
                        ),
                    ),
                    'setEvent' => array(
                        'e' => array(
                            'type' => 'Zend\\EventManager\\EventInterface',
                            'required' => true,
                        ),
                    ),
                    'setLocator' => array(
                        'locator' => array(
                            'type' => 'Zend\\Di\\Locator',
                            'required' => true,
                        ),
                    ),
                    'setBroker' => array(
                        'broker' => array(
                            'type' => null,
                            'required' => true,
                        ),
                    ),
                ),

                'Zend\Mvc\Router\RouteStackInterface' => array(
                    'instantiator' => array(
                        'Zend\Mvc\Router\Http\TreeRouteStack',
                        'factory'
                    ),
                ),

            ),
        ),
    ),
);