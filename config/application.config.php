<?php
return array(
    'modules' => array(
//        'Application',
        'ZfcTwig',       //Template engine module: defines how the template files should be processed
        'ZeDb',         //Database abstraction layer: defines how the application exchanges data with the database server
        'ZeAuth',       //Allows user accounts to log into the application
        'Core',         //Contains core functionality like homepages and default routes
        'Dots', 'DotsBlock', 'DotsPages',
        'ZeTheme',        //Contains html design elements and themes
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'config_cache_enabled' => false,
        'cache_dir' => dirname(__DIR__) . '/data/cache',
        'module_paths' => array(
            './core',
            './vendor',
        ),
    ),
    'service_manager' => array(
        'use_defaults' => true,
        'factories' => array(
        ),
    ),
);
