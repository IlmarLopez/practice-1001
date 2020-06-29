<?php
//require files
require_once('mysqlconnection.php');
require_once('role.php');
require_once(__DIR__ . '/../config/exceptions/accessdeniedexception.php');
require_once(__DIR__ . '/../config/exceptions/recordnotfoundexception.php');
require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/../config/security.php');
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

        // login
        if (func_num_args() == 2) {
            // parameters
            $username = $arguments[0];
            $password = $arguments[1];

            $query = 'SELECT id, name, photo FROM users where id = ? and password = sha1(?);'; // query
            $connection = MySqlConnection::getConnection(); // get connection
            $command = $connection->prepare($query); //prepare command (avoid injection)
            $command->bind_param('ss', $username, $password);
            $command->bind_result(
                $id,
                $name,
                $photo,
            ); //bind results
            $command->execute(); //execute
            // record found
            if ($command->fetch()) {
                $this->id = $id;
                $this->name = $name;
                $this->photo = $photo;
            } else {
                throw new AccessDeniedException($username);
            }
            mysqli_stmt_close($command); //close command
            $connection->close(); //close conection
        }

        //create object with values from the arguments
        if (func_num_args() == 3) {
            $this->id = $arguments[0];
            $this->name = $arguments[1];
            $this->photo = $arguments[2];
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
            'photo' => Config::getFileUrl('userPhotos') . $this->photo,
            'roles' => json_decode($this->getRolesToJson()),
            'token' => Security::generateToken($this->id),
        ));
    }
    public function toJsonFull()
    {
        //get roles
        $list = array(); //empty list
        foreach ($this->getRoles() as $item) {
            //add item to list
            array_push($list, json_decode($item->toJson()));
        }
        //return  JSON
        return json_encode(array(
            'id' => $this->id,
            'name' => $this->name,
            'photo' => $this->photo,
            'roles' => $list
        ));
    }


    //return a list of all the roles
    public function getRoles()
    {
        $list = array(); // empty list
        $query = 'select r.id, r.name from roles as r join usersroles as ur on r.id = ur.idRole where idUser = ?;';
        $connection = MySqlConnection::getConnection(); //connection
        $command = $connection->prepare($query); //command
        $command->bind_param('s', $this->id); //bind parameters
        $command->bind_result($id, $name); //bind results
        $command->execute();
        //read rows
        while ($command->fetch()) {
            //add readings to list
            array_push($list, new Role($id, $name));
        }
        mysqli_stmt_close($command); // close statement
        $connection->close(); // close connection
        return $list; //return list
    }

    //return a json of all the roles
    public function getRolesToJson()
    {
        $list = array(); //empty list
        foreach ($this->getRoles() as $item) {
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
        $query = 'SELECT id, name, photo FROM users order by name;';
        $connection = MySqlConnection::getConnection(); // get connection
        $command = $connection->prepare($query); //prepare command (avoid injection)
        $command->bind_result(
            $id,
            $name,
            $photo,
        ); //bind results
        $command->execute(); //execute
        //read results
        while ($command->fetch()) {
            array_push($list, new User($id, $name, $photo));
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
