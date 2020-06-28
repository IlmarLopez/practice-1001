<?php
//require files
require_once('models/user.php');
//require_once('config/exceptions/recordnotfoundexception.php');

//get
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($parameters == '') {
        //get all
        if ($action == '') {
            echo json_encode(array(
                'status' => 0,
                'users' => json_decode(User::getAllToJson())
            ));
        }
        // login
        if ($action == 'login') {
            // read headers
            $headers = getallheaders();
            // verify headers
            if (isset($headers['username']) && isset($headers['password'])) {
            } else
                echo json_encode(array('status' => 501, 'errorMessage' => 'Missing security headers'));
        }
    } else {
        //get one
        try {
            $d = new User($parameters);
            //display object
            echo json_encode(array(
                'status' => 0,
                'user' => json_decode($d->toJsonFull())
            ));
        } catch (RecordNotFoundException $ex) {
            echo json_encode(array('status' => 1, 'errorMessage' => $ex->getMessage()));
        }
    }
}
