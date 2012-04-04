<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(
                'Core\\Controller\\IndexController' => array(
                    'setEventManager' => array(
                        'events' => array(
                            'type' => 'Zend\\EventManager\\EventCollection',
                            'required' => true,
                        ),
                    ),
                    'setEvent' => array(
                        'e' => array(
                            'type' => 'Zend\\EventManager\\EventDescription',
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
                'Core\\Controller\\ErrorController' => array(
                    'setEventManager' => array(
                        'events' => array(
                            'type' => 'Zend\\EventManager\\EventCollection',
                            'required' => true,
                        ),
                    ),
                    'setEvent' => array(
                        'e' => array(
                            'type' => 'Zend\\EventManager\\EventDescription',
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
            ),
        ),
    ),
);