<?php

if (!session_id()) {
    session_start();
}

$fb = new \Facebook\Facebook([
    'app_id' => $config['fb']['id'],
    'app_secret' => $config['fb']['secret'],
    'default_graph_version' => $config['fb']['version']
]);

// get a facebook authentication access token entity
$handler = $fb->getRedirectLoginHelper();
// set state key in the get array
$_SESSION['FBRLH_state'] = $_GET['state'];
// prepare callback URL with permissions
$permission = $config['fb']['permission'];
$callback_url = $handler->getLoginUrl($config['fb']['callback_url'], $permission);