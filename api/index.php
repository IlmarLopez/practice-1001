<?php
//allow access
header('Access-Control-Allow-Origin:*');
//allow methods
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
//  allow headers
header('Access-Control-Allow-Header: username, password, token');

//REQUEST_URI=toDO despues del dominio, PHP_SELF: nombre del archivo de script ejecutándose actualmente
//dirname: nombre la ruta de un directorio padre, substr(string, start): returns a part of a string.
$requestUrl = substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])));
//
//echo $requestUrl;

//Explode request url
$urlParts = explode('/', $requestUrl);

//validate url
if (sizeof($urlParts) == 3 || sizeof($urlParts) == 4) {
    //controller
    $controller = $urlParts[1];

    //if url has action
    if (sizeof($urlParts) == 4) {
        $action = $urlParts[2];
        $parameters = $urlParts[3];
    } else {
        $action = '';
        $parameters = $urlParts[2];
    }
    //require controller file
    $controller .= 'controller.php';
    if (file_exists($controller)) {
        require_once($controller);
    } else
        echo json_encode(array(
            'status' => 998,
            'errorMessage' => 'invalid controller'
        ));
} else
    echo json_encode(array(
        'status' => 999,
        'errorMessage' => 'invalid Url'
    ));
