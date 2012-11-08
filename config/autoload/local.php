<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being comitted into version control.
 */

return array(
    'zendexperts_zetwig' => array(
        'environment_options' => array(
            'cache' => BASE_PATH . '/data/cache/twig',
        ),
    ),

    'zendexperts_zedb' => array(
        'adapter' => array(
            'driver'    => 'Pdo_Mysql', //'MySqli',
            'database'  => 'www_dotscms_dev',
            'username'  => 'root',
            'password'  => 'root'
        )
    ),

    'di' => array(
        'instance' => array(
            'Zend\Db\Adapter\Adapter' => array(
                'parameters' => array(
                    'driver' => array(
                        'driver' => 'Pdo_Mysql', //'MySqli',
                        'database' => 'www_dotscms_dev',
                        'username' => 'root',
                        'password' => 'root'
                    )
                )
            ),
        )
    ),
);
