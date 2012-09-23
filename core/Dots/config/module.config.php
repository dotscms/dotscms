<?php
return array(
    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'dots' => __DIR__ . '/../views',
        ),
        'helper_map' => array(
            'dotsNav' => 'Dots\\View\\Helper\\DotsNav',
            'dotsForm' => 'Dots\\View\\Helper\\DotsForm',
        )
    ),
);
