<?php
return [

    /**
     *
     */
    'Security' => [
        'Authentication' => [
            'enabled' => true,
            'identificationColumns' => [
                'username'
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
        'user' => '1',
        'admin' => '2'
    ],

    /**
     * Enabling the API to:
     * -
     */
    'Api' => [
        'enabled' => true,
        'extensions' => ['json'],
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
