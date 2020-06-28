<?php
//require files
require_once('models/user.php');
//require_once('config/exceptions/recordnotfoundexception.php');

//get
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($parameters == '') {
        //get all
        echo json_encode(array(
            'status' => 0,
            'users' => json_decode(User::getAllToJson())
        ));
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
