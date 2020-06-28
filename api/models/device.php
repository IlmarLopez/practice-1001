<?php
    //require files
    require_once('mysqlconnection.php');
    require_once('devicetypes.php');
    require_once('reading.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/dashboard2020/api/config/exceptions/recordnotfoundexception.php');
    class Device{
        //attributes
        private $id;
        private $name;
        private $type;
        private $ipAddress;


        //getter and setter
        public function getId(){return $this->id;}
        public function setId($id){return $this->id = $id;}
        public function getName(){return $this->name;}
        public function setName($name){return $this->name = $name;}
        public function getType(){return $this->type;}
        public function setType($type){return $this->type = $type;}
        public function getIpAddress(){return $this->ipAddress;}
        public function setIpAddress($ipAddress){return $this->ipAddress = $ipAddress;}
           
        //constructor
        public function __construct(){
            //get arguments
            $arguments= func_get_args();
            //create an empty object
            if(func_num_args()==0){
                $this->id='';
                $this->name='';
                $this->type= new DeviceTypes();
                $this->ipAddress='';
            }
            //create object with data from database
            if(func_num_args()==1){
                 //query
                $query = 'SELECT d.id, d.name, d.idType, dt.description, dt.icon, dt.minVal, 
                dt.maxVal, dt.unitOfMeasuremet, dt.chartColor, d.ipAddress 
                FROM devices as d JOIN deviceTypes AS dt on d.idType= dt.id  WHERE d.id= ?';
                $connection = MySqlConnection::getConnection(); // get connection
                $command = $connection->prepare($query); //prepare command (avoid injection)
                $command->bind_param('s', $arguments[0]); //bind parameters
                //bind results
                $command->bind_result($id, $name, $typeId, $typeDescription, $typeIcon, $typeMinValue,
                                      $typeMaxValue,$typeUnitOfMeasurement,$typeChartColor,$ipAddress); 
                $command->execute(); //execute
                //row found
                if($command->fetch()){
                    //add devices to list
                    $this->id= $id;
                    $this->name= $name;
                    $this->type= new DeviceTypes($typeId, $typeDescription, $typeIcon, $typeMinValue,
                                $typeMaxValue,$typeUnitOfMeasurement,$typeChartColor);
                    $this->ipAddress= $ipAddress;
                }
                else{
                    throw new RecordNotFoundException($arguments[0]);
                }
                mysqli_stmt_close($command); //close command
                $connection->close(); //close conection
               
            }
            //create an empty object
            if(func_num_args()==4){
                $this->id= $arguments[0];
                $this->name= $arguments[1];
                $this->type= $arguments[2];
                $this->ipAddress=$arguments[3];
            }
            //create object with data  from the arguments
        }

    //instance method
        
        //represent the object's header in json format
        public function toJson(){
            return json_encode(array(
                'id'=> $this->id,
                'name'=> $this->name,
                'type'=> json_decode($this->type->toJson()),
                'ipAddress' => $this->ipAddress
            ));
        }
        public function toJsonFull(){
            //get readings
            $list = array(); //empty list
            foreach($this->getAllReadings() as $item){
                //add item to list
               array_push($list, json_decode($item->toJson()));
           }
           //return  JSON
            return json_encode(array(
                'id'=> $this->id,
                'name'=> $this->name,
                'type'=> json_decode($this->type->toJson()),
                'ipAddress' => $this->ipAddress,
                'readings'=> $list
            ));
        }

       
        //return a list of all the readings
        public function getAllReadings(){
            $list= array(); // empty list
            $query= 'SELECT dateTime, value FROM `readings` WHERE idDevice= ? ORDER BY dateTime DESC;';
            $connection= MySqlConnection::getConnection();//connection
            $command= $connection->prepare($query); //command
            $command->bind_param('s', $this->id); //bind parameters
            $command->execute();
            $command->bind_result($dateTime, $value); //bind results
            //read rows
            while($command->fetch()){
                //add readings to list
                array_push($list, new Reading($dateTime, $value));
            }
            return $list; //return list
        }
        //add devices
        public function add(){
            $query = 'INSERT INTO devices (id, name, idType, ipAddress) VALUES (?, ?,?, ?)';
            $connection = MySqlConnection::getConnection(); // get connection
            $command = $connection->prepare($query); //prepare command (avoid injection)
            //bind parameters
            $idType= $this->type->getId();
            $command->bind_param('ssss',
                $this->id, 
                $this->name,
                $idType,
                $this->ipAddress); 
            $result = $command->execute(); //execute
            mysqli_stmt_close($command); //close command
            $connection->close(); //close conection
            return $result;
        }
        //return a json of all the readings
        public static function getAllReadingsToJson(){
            $list = array(); //empty list
            foreach($this->getAllReadings() as $item){
                //add item to list
               array_push($list, json_decode($item->toJson()));
           }
           return json_encode($list);
        }

    //class methods

        //returns a list of all the devices
         public static function getAll(){
             $list = array(); //empty list
            //query
             $query = 'SELECT d.id, d.name, d.idType, dt.description, dt.icon,
                       dt.minVal, dt.maxVal, dt.unitOfMeasuremet, dt.chartColor, d.ipAddress 
                       FROM devices as d JOIN deviceTypes AS dt on d.idType= dt.id  order by d.name';
             $connection = MySqlConnection::getConnection(); // get connection
             $command = $connection->prepare($query); //prepare command (avoid injection)
             $command->bind_result($id, $name, $typeId, $typeDescription, $typeIcon, $typeMinValue,
                                $typeMaxValue,$typeUnitOfMeasurement,$typeChartColor, $ipAddress); //bind results
             $command->execute(); //execute
             //read results
             while($command->fetch()){
                 //add devices to list
                 $type= new DeviceTypes($typeId, $typeDescription, $typeIcon, $typeMinValue,
                                        $typeMaxValue,$typeUnitOfMeasurement,$typeChartColor,);
                 array_push($list, new Device($id, $name, $type, $ipAddress));
             }
             mysqli_stmt_close($command); //close command
             $connection->close(); //close conection
             return $list;
         }
        
         //return a json of all the devices
         public static function getAllToJson(){
             $list = array(); //empty list
             foreach(self::getAll() as $item){
                 //add item to list
                array_push($list, json_decode($item->toJson()));
            }
            return json_encode($list);
         }
    }
?>