<?php

return [
    'database' => [
        'dsn' => 'mysql:host=localhost;dbname=fblogin',
        'username' => 'root',
        'password' => 'root123'
    ],
    'fb' => [
        'id' => '775224196325038',
        'secret' => '91c8302ff801ea38ec67019ebada1afa',
        'version' => 'v2.10',
        'permission' => ['email'],
        'callback_url' => 'http://localhost/social-media-login/fb-callback.php' // add s to http
    ]
];