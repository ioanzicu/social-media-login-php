<?php

require_once __DIR__ . '/header.php';
require_once __DIR__ . '/app/fb_setup.php'; // $handler
require_once __DIR__ . '/app/database.php';
$errors = "";

if (!session_id()) {
    session_start();
}

try {
    // Get the Access Token
    $userAccessToken = $handler->getAccessToken();

    /// Check if the User is not Authorized
    if (!$userAccessToken) {
        // Unauthorized
        if ($handler->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo $errors = "Errors " . $handler->getError() . " Reason: "
                . $handler->getErrorReason() . " Desc: "
                . $handler->getErrorDescription();
        } else {
            // If not Error ->  is a Bad Request
            header('HTTP/1.0 400 Bad Request');
            echo $errors = "Something whent wrong. Try again later.";
        }
    }

    // Check the Expiring info of the Expiring Token
    // $oauth -> is a manager of the Facebook Token
    $oauth = $fb->getOAuth2Client();

    $tokenMetadata = $oauth->debugToken($userAccessToken);

    // Validate API id and Access Token Expiration
    $tokenMetadata->validateAppId($config['fb']['id']);
    $tokenMetadata->validateExpiration();
    // If the Token is not Long Lived, we are changing it to be Long Lived
    if (!$userAccessToken->isLongLived()) {
        $userAccessToken = $oauth->getLongLivedAccessToken($userAccessToken);
    }
} catch (\Facebook\Exceptions\FacebookResponseException $ex) {
    echo $errors = "Facebook graph returned an error: " . $ex->getMessage();
} catch (\Facebook\Exceptions\FacebookSDKException $ex) {
    echo $errors = "Facebook SDK returned an error: " . $ex->getMessage();
}