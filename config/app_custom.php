<?php

/**
 * Optionally define TinyAuthorize role constants here so you can use them in
 * your code. Make sure these ids match the actual role ids.
 */
define('ROLE_USERS', 1);
define('ROLE_ADMINS', 2);
define('ROLE_SUPERADMINS', 3);

/**
 * Configure your app
 */
return [

    /**
     * Define roles here if you do NOT want to use a Roles database table.
     */
    'Roles' => [
        'user' => ROLE_USERS,
        'admin' => ROLE_ADMINS,
        'superadmin' => ROLE_SUPERADMINS
    ],

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
            'enabled' => true,
            'roleColumn' => 'role_id', // only used in single-role mode
            'rolesTable' => 'UserRoles', // name of Configure key (or database table class) holding available roles
            'multiRole' => true // requires a pivot table (see /config/Migrations)
        ]
    ],

    /**
     * ADMIN prefix configuration
     */
     'Admin' => [
         'enabled' => true
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
