<?php
    //requiere files 
    require_once('models/device.php');

    //get 
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){
        if($parameters == ''){
            //get all
            echo json_encode(array(
                'status' => 0,
                'devices' => json_decode(Device::getAllToJson())
            ));
        }
        else {
            //get one
            try {
                echo $parameters;
                $d = new Device($parameters);
    
                //display object 
                echo json_encode(array(
                    'status' => 0,
                    'device' => json_decode($d->toJson())
                ));
            }
            catch(RecordNotFountException $ex) {
                echo json_encode(array(
                    'status' => 1,
                    'errorMessage' => $ex->getMessage()
                ));
            }
           
        }
    }

    //post 
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo 'post';
    }

    //PUT 
    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
        if ($action == '')
        echo 'put';
        if ($action == 'reset') {
            echo 'reset device'.$parameters;            
        }
    }

    //DELETE 
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
        echo 'delete';
    }
?>