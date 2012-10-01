<?php
/**
 * This file is part of ZeAuth
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ZeAuth;

return array(
    'ze-auth' => array(
        'user_model_class'          => __NAMESPACE__ . '\Db\Model\User',
        'identity_type'             => 'username', //username, email_address, both
        'remember_me'               => 60*60*24*2,
        'enable_display_name'       => false,
        'require_activation'        => true,
        'login_after_registration'  => true,
        'registration_form_captcha' => true,
        'password_hash_algorithm'   => 'plain', // plain, sha1, md5, blowfish, sha512, sha256
        'home_route'                => 'home',
        'restricted_routes'         => array(),
        'unrestricted_routes'       => array(
            'ze-auth' => array('ze-auth-auth')
        )
    ),

    // View Manager Service
    'view_manager' => array(
        'template_path_stack' => array(
            'ze-auth'       => __DIR__ . '/../views',
        ),
        'helper_map'=>array(
            'auth'=> __NAMESPACE__ . '\Plugin\Auth',
        )
    ),

    // Controller Service
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\Auth' => __NAMESPACE__ . '\Controller\AuthController',
        ),
    ),

    'controller_plugins'=>array(
        'factories'=>array(
            'auth' => __NAMESPACE__ . '\Plugin\AuthFactory',
        )
    ),

    'di' => array(
        'instance' => array(
            'alias' => array(
                'ZeAuthMapperUser'   => __NAMESPACE__ . '\Db\Mapper\User',
                'ZeAuthModelUser'    => __NAMESPACE__ . '\Db\Model\User',
            ),
        ),
    ),
);
