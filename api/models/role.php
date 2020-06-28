<?php
//require files
require_once('mysqlconnection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dashboard2020/api/config/exceptions/recordnotfoundexception.php');
class Role
{
    //attributes
    private $id;
    private $name;


    //getter and setter
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        return $this->id = $id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        return $this->name = $name;
    }

    //constructor
    public function __construct()
    {
        //get arguments
        $arguments = func_get_args();
        //create an empty object
        if (func_num_args() == 0) {
            $this->id = '';
            $this->name = '';
        }
        //create object with data from database
        if (func_num_args() == 1) {
            //query
            $query = 'SELECT id, name 
                FROM roles WHERE id= ?';
            $connection = MySqlConnection::getConnection(); // get connection
            $command = $connection->prepare($query); //prepare command (avoid injection)
            $command->bind_param('s', $arguments[0]); //bind parameters
            //bind results
            $command->bind_result($id, $name);
            $command->execute(); //execute
            //row found
            if ($command->fetch()) {
                //add devices to list
                $this->id = $id;
                $this->name = $name;
            } else {
                throw new RecordNotFoundException($arguments[0]);
            }
            mysqli_stmt_close($command); //close command
            $connection->close(); //close conection

        }
        //create an object
        if (func_num_args() == 2) {
            $this->id = $arguments[0];
            $this->name = $arguments[1];
        }
        //create object with data  from the arguments
    }

    //instance method

    //represent the object's header in json format
    public function toJson()
    {
        return json_encode(array(
            'id' => $this->id,
            'name' => $this->name,
        ));
    }
    public function toJsonFull()
    {
        //return  JSON
        return json_encode(array(
            'id' => $this->id,
            'name' => $this->name,
        ));
    }

    //class methods

    //returns a list of all the devices
    public static function getAll()
    {
        $list = array(); //empty list
        //query
        $query = 'SELECT id, name FROM roles';
        $connection = MySqlConnection::getConnection(); // get connection
        $command = $connection->prepare($query); //prepare command (avoid injection)
        $command->bind_result(
            $id,
            $name,
        ); //bind results
        $command->execute(); //execute
        //read results
        while ($command->fetch()) {
            //add devices to list
            array_push($list, new Role($id, $name));
        }
        mysqli_stmt_close($command); //close command
        $connection->close(); //close conection
        return $list;
    }

    //return a json of all the devices
    public static function getAllToJson()
    {
        $list = array(); //empty list
        foreach (self::getAll() as $item) {
            //add item to list
            array_push($list, json_decode($item->toJson()));
        }
        return json_encode($list);
    }
}
