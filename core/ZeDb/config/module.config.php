<?php
return array(
    'di'    => array(
        'instance' => array(
            'alias' => array(
                'zedb' => 'ZeDb\Registry'
            ),
            'ZeDb\Registry' => array(
                'parameters' => array(
                    'models' => array(),
                ),
            ),
        ),
    ),
);
