<?php
return array(
    'modules' => array(
        'ZeTwig',
        'ZeDb',
        'Core',
        'ZeTpl', //Template
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'cache_dir'            => dirname(__DIR__) . '/data/cache',
        'module_paths' => array(
            './templates',
            './modules',
            './core',
            './vendor',
        ),
    ),
);
