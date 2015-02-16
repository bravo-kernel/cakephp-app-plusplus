<?php

/**
 * Constants used for TinyAuth role based authorization
 */
define('ROLE_USERS', 1);
define('ROLE_ADMINS', 2);
define('ROLE_SUPERADMINS', 9);


/**
 * Configure your app
 */
return [

    /**
     *
     */
    'Security' => [
        'Authentication' => [
            'enabled' => true,
            'identificationColumns' => [
                'username',
                'email'
            ]
        ],
        'Authorization' => [
            'enabled' => true
        ]
    ],

    /**
     * Roles to use when authorization is enabled.
     */
    'Roles' => [
        'user' => ROLE_USERS,
        'admin' => ROLE_ADMINS,
        'supergirls' => ROLE_SUPERADMINS
    ],

    /**
     * ADMIN prefix configuration
     */
     'Admin' => [
         'enabled' => true,
    ],

    /**
     * API prefix configuration
     */
    'Api' => [
        'enabled' => true,
        'extensions' => ['json', 'xml'],
        'jwt' => true
    ],

    /**
     * Misc settings
     */
    'Datasources' => [
        'default' => [
            'quoteIdentifiers' => true,
        ],
        'test' => [
            'quoteIdentifiers' => true,
        ]
    ],

    'Session' => [
        'timeout' => 3 * DAY,
        'cookieTimeout' => MONTH
    ],

    'Passwordable'  => [
        'passwordHasher' => ['className' => 'Fallback', 'hashers' => ['Default', 'Weak']]
    ],

    'Config' => array(
        'pageName' => 'CakeFest App',
        'adminEmail' => '', // Overwrite in app_local.php
        'adminName' => 'Mark',
        'rememberMe' => false,
    ),

    'Asset' => array(
        'js' => 'buffer'
    ),

  // Please provide the following using the app_locale.php which is not under version control
    'Email' => array(
        'Smtp' => array(
            'host' => '',
            'username' => '',
            'password' => ''
        )
    )
];
