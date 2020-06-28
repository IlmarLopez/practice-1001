<?php
 //require files
 require_once('models/device.php');
 //require_once('config/exceptions/recordnotfoundexception.php');

 //get
if($_SERVER['REQUEST_METHOD']=='GET'){
    if($parameters==''){
        //get all
        echo json_encode(array(
            'status' =>0,
            'devices'=> json_decode(Device::getAllToJson())
        ));
    }else{
        //get one
        try{
            $d= new Device($parameters);
             //display object
            echo json_encode(array(
                'status'=>0,
                'device'=>json_decode($d->toJsonFull())
            ));
            
        }catch(RecordNotFoundException $ex){
            echo json_encode(array('status'=>1, 'errorMessage'=>$ex->getMessage()));
        }
    }
}
//post
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['idtype']) && isset($_POST['ipaddress'])){
        //validation
        $error= false;
        //validate type
        try{
            $dt= new DeviceTypes($_POST['idtype']);
            echo 'ok';
        }catch(RecordNotFoundException $ex){
            $error= true;
            echo json_encode(array('status'=> 999,'errorMessage'=>'Could not find id type'));
        }
        //add device
        if(!$error){
            //create device
            $d = new Device();
             //read parameters
            $id= $_POST['id'];
            $name= $_POST['name'];
            $ipAddress= $_POST['ipaddress'];
            
            //assign values to attributes
            $d->setId($id);
            $d->setName($name);
            $d->setType($dt);
            $d->setIpAddress($ipAddress);
            if($d->add()){
                echo json_encode(array('status'=>0, 'message'=>'Device added successfully'));
            }else
            echo json_encode(array('status'=>997, 'error message'=>'Could not add device'));  
        }
    }else{
        echo json_encode(array(
            'status'=> 999,
            'errorMessage'=>'Missing parameters'
        ));
     }
    
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
