<?php
//require files
require_once('mysqlconnection.php');
require_once('userrole.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/dashboard2020/api/config/exceptions/recordnotfoundexception.php');
class User
{
    //attributes
    private $id;
    private $name;
    private $roles;
    private $photo;
    private $password;


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
    public function getPhoto()
    {
        return $this->photo;
    }
    public function setPhoto($photo)
    {
        return $this->photo = $photo;
    }
    public function getRoles()
    {
        return $this->roles;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        return $this->password = $password;
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
            $this->roles = [];
            $this->photo = '';
            $this->password = '';
        }
        //create object with data from database
        if (func_num_args() == 1) {
            //query
            $query = 'SELECT id, name, photo, password FROM users WHERE id= ?';
            $connection = MySqlConnection::getConnection(); // get connection
            $command = $connection->prepare($query); //prepare command (avoid injection)
            $command->bind_param('s', $arguments[0]); //bind parameters
            //bind results
            $command->bind_result(
                $id,
                $name,
                $photo,
                $password,
            );
            $command->execute(); //execute
            //row found
            if ($command->fetch()) {
                //add devices to list
                $this->id = $id;
                $this->name = $name;
                $this->photo = $photo;
                $this->password = $password;
            } else {
                throw new RecordNotFoundException($arguments[0]);
            }
            mysqli_stmt_close($command); //close command
            $connection->close(); //close conection

        }
        //create an empty object
        if (func_num_args() == 4) {
            $this->id = $arguments[0];
            $this->name = $arguments[1];
            $this->photo = $arguments[2];
            $this->password = $arguments[3];
            $this->roles = $this->getAllRoles();
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
            'roles' => $this->roles,
            'photo' => $this->photo,
            'password' => $this->password
        ));
    }
    public function toJsonFull()
    {
        //get roles
        $list = array(); //empty list
        foreach ($this->getAllRoles() as $item) {
            //add item to list
            array_push($list, json_decode($item->toJson()));
        }
        //return  JSON
        return json_encode(array(
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo,
            'password' => $this->password,
            'roles' => $list
        ));
    }


    //return a list of all the roles
    public function getAllRoles()
    {
        $list = array(); // empty list
        $query = 'select name from roles as r inner join usersroles as ur on r.id = ur.idRole where idUser = ?;';
        $connection = MySqlConnection::getConnection(); //connection
        $command = $connection->prepare($query); //command
        $command->bind_param('s', $this->id); //bind parameters
        $command->execute();
        $command->bind_result($name); //bind results
        //read rows
        while ($command->fetch()) {
            //add readings to list
            array_push($list, new UserRole($name));
        }
        return $list; //return list
    }

    //return a json of all the roles
    public static function getAllRolesToJson()
    {
        $list = array(); //empty list
        foreach ($this->getAllRoles() as $item) {
            //add item to list
            array_push($list, json_decode($item->toJson()));
        }
        return json_encode($list);
    }

    //class methods

    //returns a list of all the users
    public static function getAll()
    {
        $list = array(); //empty list
        //query to users
        $query = 'SELECT id, name, photo, password FROM users;';
        $connection = MySqlConnection::getConnection(); // get connection
        $command = $connection->prepare($query); //prepare command (avoid injection)
        $command->bind_result(
            $id,
            $name,
            $photo,
            $password,
        ); //bind results
        $command->execute(); //execute
        //read results
        while ($command->fetch()) {
            array_push($list, new User($id, $name, $photo, $password));
        }
        mysqli_stmt_close($command); //close command
        $connection->close(); //close conection
        return $list;
    }

    //return a json of all the users
    public static function getAllToJson()
    {
        $list = array(); //empty list
        foreach (self::getAll() as $item) {
            //add item to list
            array_push($list, json_decode($item->toJsonFull()));
        }
        return json_encode($list);
    }
}
