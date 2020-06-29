<?php
//allow access
header('Access-Control-Allow-Origin:*');
//allow methods
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// allow headers
header('Access-Control-Allow-Headers: username, password, token');

// require files
require_once('config/security.php');

// read headers
$headers = getallheaders();

// read request URL
$requestUrl = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])));

//Explode request url
$urlParts = explode('/', $requestUrl);

//validate url
if (sizeof($urlParts) == 3 || sizeof($urlParts) == 4) {
    //controller
    $controller = $urlParts[1];
    // url has action
    if (sizeof($urlParts) == 4) {
        $action = $urlParts[2];
        $parameters = $urlParts[3];
    } else {
        $action = '';
        $parameters = $urlParts[2];
    }
    // access
    $access = true;
    // not login
    if ($controller != 'user' && $action != 'login') {
        if (isset($headers['username'])  && isset($headers['token'])) {
            if ($headers['token'] != Security::generateToken($headers['username'])) {
                $access = false;
                echo json_encode(array(
                    'status' => 500,
                    'errorMessage' => 'Invalid token'
                ));
            }
        } else {
            $access = false;
            echo json_encode(array(
                'status' => 501,
                'errorMessage' => 'Missing security headers'
            ));
        }
    }
    // access granted
    if ($access) {
        //require controller file
        $controller .= 'controller.php';
        if (file_exists($controller)) {
            require_once($controller);
        } else
            echo json_encode(array(
                'status' => 998,
                'errorMessage' => 'invalid controller'
            ));
    }
} else
    echo json_encode(array(
        'status' => 999,
        'errorMessage' => 'invalid Url'
    ));
