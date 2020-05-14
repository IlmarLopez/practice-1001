<?php
    require_once('mysqlconnection.php');
    require_once('exceptions/recordnotfoundexception.php');
    class Device {
        // attributes
        private $id;
        private $name;
        private $ipAddress;


        //getters and setters
        public function getId(){ return $this->id; }
        public function setId($id){ $this->id = $id; }

        public function getName(){ return $this->name; }
        public function setName($name){ $this->name = $name; }

        public function getIpAddress(){ return $this->IpAddress; }
        public function setIpAddress($ipAddress){ $this->ipAddress = $ipAddress; }

        //constructor
        public function __construct(){
            //get arguments
            $arguments = func_get_args();
            //create empty object 
            if (func_num_args() == 0){
                $this->id = '';
                $this->name = '';
                $this->ipAddress = '';
            }

            // create object with data from the database
            if (func_num_args() == 1) {
              // query
              $query = 'select id, name, ipAddress from devices where id = ?';
              // get connection
              $connection = MySqlConnection::getConnection();
              // prepare command
              $command = $connection->prepare($query);
              // bind parameters
              $command->bind_param("s", $arguments[0]);
              // bind results
              $command->bind_result($id, $name, $ipAddress);

              echo $arguments[0];

              // execute
              $command->execute();

              // row found
              if($command-fetch()) {
                  // add device to list
                  $this->id = $id;
                  $this->name = $name;
                  $this->ipAddress = $ipAddress;
              } else {
                throw new RecordNotFountException($arguments[0]);
              }

              // close command
              mysqli_stmt_close($command);
              // close connection
              $connection->close();
            }

            //create object with data from the arguments
            if(func_num_args() == 3){
                $this->id = $arguments[0];
                $this->name = $arguments[1];
                $this->ipAddress = $arguments[2];
            }
        }

        //intance methods 

        //represents the object in JSON format
        public function toJson(){
            return json_encode(array(
                'id' => $this->id,
                'name' => $this->name,
                'ipAddress' => $this->ipAddress
            ));
        }

        // class methods

        // returns a list of all the devices
        public static function getAll() {
            $list = array(); // empty list

            // query
            $query = 'select id, name, ipAddress from devices order by name';
            // get connection
            $connection = MySqlConnection::getConnection();
            // prepare command
            $command = $connection->prepare($query);
            // bind results
            $command->bind_result($id, $name, $ipAddress);

            // execute
            $command->execute();

            // read results
            while($command->fetch()) {
                // add device to list
                array_push($list, new Device($id, $name, $ipAddress));
            }

            // close command
            mysqli_stmt_close($command);
            // close connection
            $connection->close();

            return $list; // return list
        }

        // returnsa JSON array of all the devices
        public static function getAllToJson() {
            $list = array();

            foreach(self::getAll() as $item) {
                // add item to list
                array_push($list, json_decode($item->toJson()));
            }

            return json_encode($list);
        }
    }
?>