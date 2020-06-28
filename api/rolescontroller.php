<?php
 //require files
 require_once('models/role.php');
 //require_once('config/exceptions/recordnotfoundexception.php');

 //get
if($_SERVER['REQUEST_METHOD']=='GET'){
    if($parameters==''){
        //get all
        echo json_encode(array(
            'status' =>0,
            'roles'=> json_decode(Role::getAllToJson())
        ));
    }else{
        //get one
        try{
            $d= new Role($parameters);
             //display object
            echo json_encode(array(
                'status'=>0,
                'role'=>json_decode($d->toJsonFull())
            ));
            
        }catch(RecordNotFoundException $ex){
            echo json_encode(array('status'=>1, 'errorMessage'=>$ex->getMessage()));
        }
    }
}
