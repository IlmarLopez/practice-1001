<?php
 //require files
 require_once('models/devicetypes.php');
 //require_once('config/exceptions/recordnotfoundexception.php');

 //get
if($_SERVER['REQUEST_METHOD']=='GET'){
    if($parameters==''){
        //get all
        echo json_encode(array(
            'status' =>0,
            'devicesTypes'=> json_decode(DeviceTypes::getAllToJson())
        ));
    }else{
        //get one
        try{
            $d= new DeviceTypes($parameters);
             //display object
            echo json_encode(array(
                'status'=>0,
                'device'=>json_decode($d->toJson())
            ));
        }catch(RecordNotFoundException $ex){
            echo json_encode(array('status'=>1, 'errorMessage'=>$ex->getMessage()));
        }
    }
}
//post
if($_SERVER['REQUEST_METHOD']=='POST'){
    echo 'post';
}

//put
if($_SERVER['REQUEST_METHOD']=='PUT'){
    if($action=='')
        echo 'put';
    if($action=='reset'){
        echo 'reset device'.$parameters;
    }
}
//delete
if($_SERVER['REQUEST_METHOD']=='DELETE'){
    echo 'delete';
}

?>
