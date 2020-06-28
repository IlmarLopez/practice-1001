<?php
    //require files
    require_once('mysqlconnection.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/dashboard2020/api/config/exceptions/recordnotfoundexception.php');
    require_once(__DIR__.'/../config/config.php'); 
    class DeviceTypes{
        //attributes
        private $id;
        private $description;
        private $icon;
        private $minVal;
        private $maxVal;
        private $unitOfMeasurement;
        private $chartColor;



        //getter and setter
        public function getId(){return $this->id;}
        public function setId($id){return $this->id = $id;}
        public function getName(){return $this->description;}
        public function setName($description){return $this->description = $description;}
        public function getIpAddress(){return $this->icon;}
        public function setIpAddress($icon){return $this->icon = $icon;}
        public function getMinValue(){return $this->minVal;}
        public function getMaxValue(){return $this->maxVal;}
        public function getUnitOfMeasurement(){return $this->unitOfMeasurement;}
        public function getChartColor(){return $this->chartColor;}
           
        //constructor
        public function __construct(){
            //get arguments
            $arguments= func_get_args();
            //create an empty object
            if(func_num_args()==0){
                $this->id='';
                $this->description='';
                $this->icon='';
            }
            //create object with data from database
            if(func_num_args()==1){
                 //query
                $query = 'SELECT id, description, icon, minVal, maxVal, unitOfMeasurement, chartColor
                 FROM `deviceTypes` where id= ?';
                // get connection
                $connection = MySqlConnection::getConnection();
                //prepare command (avoid injection)
                $command = $connection->prepare($query);
                //bind parameters
                $command->bind_param('s', $arguments[0]);
                //bind results
                $command->bind_result($id, $description, $icon, $minVal, $maxVal, $unitOfMeasurement, $chartColor);
                //execute
                $command->execute();
                //read results
                if($command->fetch()){
                    //add devices to list
                    $this->id= $id;
                    $this->description= $description;
                    $this->icon= $icon;
                    $this->minVal=$minVal;
                    $this->minVal=$minVal;
                    $this->maxVal=$maxVal;
                    $this->unitOfMeasurement=$unitOfMeasurement;
                }
                else{
                    throw new RecordNotFoundException($arguments[0]);
                }
                //close command
                mysqli_stmt_close($command);
                //close conection
                $connection->close();
               
            }
            //create an empty object
            if(func_num_args()==7){
                $this->id= $arguments[0];
                $this->description= $arguments[1];
                $this->icon=$arguments[2];
                $this->minValue=$arguments[3];
                $this->maxValue=$arguments[4];
                $this->unitOfMeasurement=$arguments[5];
                $this->chartColor=$arguments[6];
            }
            //create object with data  from the arguments
        }

    //instance method
        
        //represent the object in json format
        public function toJson(){
            return json_encode(array(
                'id'=> $this->id,
                'description'=> $this->description,
                'icon' => Config::getFileUrl('icons').$this->icon,
                'description'=> $this->description,
                'minValue'=> $this->minValue,
                'maxValue'=> $this->maxValue,
                'unitOfMeasurement'=> $this->unitOfMeasurement,
                'chartColor'=> $this->chartColor,
            ));
        }


    //class methods

        //returns a list of all the devices
         public static function getAll(){
             $list = array(); //empty list
            //query
             $query = 'SELECT id, description, icon, minVal, maxVal, unitOfMeasurement, chartColor
              FROM `deviceTypes` order BY id';
             // get connection
             $connection = MySqlConnection::getConnection();
             //prepare command (avoid injection)
             $command = $connection->prepare($query);
             //bind results
             $command->bind_result($id, $description, $icon, $minVal, $maxVal, $unitOfMeasurement, $chartColor);
             //execute
             $command->execute();
             //read results
             while($command->fetch()){
                 //add devices to list
                 array_push($list, new DeviceTypes($id, $description, $icon,
                             $minVal, $maxVal, $unitOfMeasurement, $chartColor));
             }
             //close command
             mysqli_stmt_close($command);
             //close conection
             $connection->close();
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
