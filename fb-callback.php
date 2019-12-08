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
            $errors = "Errors " . $handler->getError() . " Reason: "
                . $handler->getErrorReason() . " Description: "
                . $handler->getErrorDescription();
        } else {
            // If not Error ->  is a Bad Request
            header('HTTP/1.0 400 Bad Request');
            $errors = "Something whent wrong. Try again later.";
        }
    } else if ($userAccessToken) {

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

        // Login or Sign Up
        $response = $fb->get('/me?fields=id,email,name,picture.width(300).height(300)', (string) $userAccessToken);
        $user = $response->getGraphUser();
        // Find user in the db
        $exists = $db->prepare("SELECT * FROM users WHERE provider_id = :pid OR email = :email");
        // Check if there is an User with such Email
        $user->getEmail() != "" ? $email = $user->getEmail() : $email = "xxxx";
        // redirect to the index.php
        $exists->execute([':pid' => $user->getId(), ':email' => $email]);

        if ($rs = $exists->fetch()) {
            $_SESSION['avatar'] = $rs['avatar'];
            $_SESSION['username'] = $rs['username'];
            $_SESSION['id'] = $rs['id'];
            // clear errors string
            if (isset($_SESSION['errors'])) unset($_SESSION['errors']);
            // redirect
            header('Location: index.php');
        } else {
            $insertQuery = "INSERT INTO users (username, email, provider, provider_id, avatar) 
                        VALUES(:username, :email, :provider, :provider_id, :avatar)";
            $statement = $db->prepare($insertQuery);
            $avatar = $user->getPicture();
            $statement->execute([
                ':username' => $user->getName(),
                ':email' => $user->getEmail(),
                ':provider' => 'Facebook',
                ':provider_id' => $user->getId(),
                ':avatar' => $avatar->getUrl()
            ]);

            if ($statement->rowCount() == 1) {
                $_SESSION['avatar'] = $avatar->getUrl();
                $_SESSION['username'] = $user->getName();
                $_SESSION['id'] = $user->getId();

                // clear errors string
                if (isset($_SESSION['errors'])) unset($_SESSION['errors']);
                // redirect
                header('Location: index.php');
            }
        }
    } else {
        $_SESSION['errors'] = 'You did not authorize';
        header('Location: index.php');
    }
} catch (\Facebook\Exceptions\FacebookResponseException $ex) {
    $errors = "Facebook graph returned an error: " . $ex->getMessage();
} catch (\Facebook\Exceptions\FacebookSDKException $ex) {
    $errors = "Facebook SDK returned an error: " . $ex->getMessage();
} catch (PDOException $ex) {
    $errors = "PDO Error: " . $ex->getMessage();
}
var_dump($userAccessToken);

if ($errors != '') {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
}