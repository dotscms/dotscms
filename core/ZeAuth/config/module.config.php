<?php
return array(
    'ze-auth' => array(
        'user_model_class'          => 'ZeAuth\Db\Model\User',
        'identity_type'             => 'username', //username, email_address, both
        'remember_me'               => 60*60*24*2,
        'enable_display_name'       => false,
        'require_activation'        => true,
        'login_after_registration'  => true,
        'registration_form_captcha' => true,
        'password_hash_algorithm'   => 'sha1', // sha1, md5, blowfish, sha512, sha256
        'home_route'=>'home',
        'restricted_routes'         => array(),
        'unrestricted_routes'       => array(
            'ze-auth'=>array('ze-auth-auth')
        )
    ),
    'di' => array(
        'instance' => array(
            'alias' => array(
                'ze-auth-auth'          => 'ZeAuth\Controller\AuthController',
                'ze-auth-form_login'    => 'ZeAuth\Form\Login',
                'ze-auth-service_auth'  => 'ZeAuth\Service\Auth',
                'ze-auth-mapper_user'   => 'ZeAuth\Db\Mapper\User',
                'ze-auth-model_user'    => 'ZeAuth\Db\Model\User',
                'ze-auth-crypt'         => 'ZeAuth\Crypt',
                'ze-auth-db'            => 'Zend\Db\Adapter\PdoMysql',
            ),

            'Zend\View\Resolver\TemplateMapResolver' => array(
                'parameters' => array(
                    'map'  => array(

                    ),
                ),
            ),
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'ze-auth' => __DIR__ . '/../views',
                    ),
                ),
            ),
            #NOT USED
//            'Zend\Mvc\Controller\PluginLoader' => array(
//                'parameters' => array(
//                    'map' => array(
//                        'user'        => 'ZeAuth\View\Helper\User',
//                    ),
//                ),
//            ),
//            'ZeAuth\View\Helper\User' => array(
//                'parameters' => array(
//
//                ),
//            ),
        ),
    ),
);
