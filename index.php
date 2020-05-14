<?php   
    //Allow access
    header('Acess-Control-Allow-Origin:*');

    //Allow methods
    header('Acess-Control-Allow-Methods: GET, POST, PUT, DELETE');

    //read request URL
    $requestUrl = substr($_SERVER['REQUEST_URI'],strlen(dirname($_SERVER['PHP_SELF'])));
    //explote requesturl
    $urlParts = explode('/', $requestUrl);

    //validate url
    if(sizeof($urlParts) == 3 || sizeof($urlParts) == 4){
        //controller
        $controller = $urlParts[1];
        //url has action
        if(sizeof($urlParts) == 4){
            $action = $urlParts[2];
            $parameters = $urlParts[3];
        }
        else{
            $action = '';
            $parameters = $urlParts[2];
        }
        //require controller file
        $controller .= 'controller.php';
        echo $controller;
        if(file_exists($controller)){
            require_once($controller);
        }
        else{
            echo json_encode(array(
                'status' => 998,
                'errorMessage' => 'invalid controller'
            ));
        }
        
    }
    else{
        echo json_encode(array(
            'status' => 999,
            'errorMessage' => 'invalid URL'
        ));
    }
?>

