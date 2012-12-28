<?php
return array(
    'modules' => array(
        'ZeTheme', //Contains html design elements and themes
        'ZfcTwig', //Template engine module: defines how the template files should be processed
        'ZeDb',             //Database abstraction layer: defines how the application exchanges data with the database server
        'ZeAuth',           //Allows user accounts to log into the application
        'Dots', 'DotsPages', 'DotsBlock', 'DotsHtmlBlock', 'DotsImageBlock', 'DotsLinkBlock', 'DotsNavBlock', 'DotsSlideshow',
    ),
    'module_listener_options' => array(
        //configure caching
        'config_cache_enabled' => true,
        'config_cache_key' => 'global',
        'cache_dir' => 'data/cache',

        'config_glob_paths' => array(
            'config/autoload/{,*.}global.php',
            'config/autoload/{,*.}local.php',
        ),
        'module_paths' => array(
            './modules',
            './vendor',
        ),
    ),
    'service_manager' => array(
        'use_defaults' => true,
        'factories' => array(
        ),
    ),
);
