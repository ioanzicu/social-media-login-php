<?php

$fb = new \Facebook\Facebook([
    'app_id' => $config['fb']['id'],
    'app_secret' => $config['fb']['secret'],
    'default_graph_version' => $config['fb']['version']
]);

// get a facebook authentication access token entity
$handler = $fb->getRedirectLoginHelper();
// prepare callback URL with permissions
$permission = $config['fb']['permission'];
$callback_url = $handler->getLoginUrl($config['fb']['callback_url'], $permission);