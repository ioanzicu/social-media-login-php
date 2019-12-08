<?php

return [
    'database' => [
        'dsn' => 'mysql:host=localhost;dbname=fblogin',
        'username' => 'root',
        'password' => 'root123'
    ],
    'fb' => [
        'id' => '',
        'secret' => '',
        'version' => 'v2.10',
        'permission' => ['email'],
        'callback_url' => 'http://localhost/social-media-login/fb-callback.php' // add s to http
    ]
];