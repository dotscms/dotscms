<?php
return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
            ),
            'Zend\View\HelperBroker' => array(
                'parameters' => array(
                    'loader' => 'Zend\View\HelperLoader',
                ),
            ),
            'Zend\View\HelperLoader' => array(
                'parameters' => array(
                    'map' => array(
                        'zedebug'        => 'ZeDebug\View\Helper\Debug',
                    ),
                ),
            ),
        ),
    ),
);