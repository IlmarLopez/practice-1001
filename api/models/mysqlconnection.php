
<?php
    class MySqlConnection{
        //returns a connection to  a mysql database
        public static function getConnection(){
            //read config file
            $config= file_get_contents(__DIR__.'/../config/config.json');
            //parse to json
            $configJson = json_decode($config, true);
            //check config structure
            if(isset($configJson['mySqlConnection'])){
                $mySql= $configJson['mySqlConnection'];
                //SERVER
                if(isset($mySql['server'])){
                    $server= $mySql['server'];
                }else{
                    echo json_encode(array('status'=>999, 'errorMessage'=> 'MySql server name not found'));
                    die;
                }
                //database
                if(isset($mySql['database'])){
                    $database= $mySql['database'];
                }else{
                    echo json_encode(array('status'=>997, 'errorMessage'=> 'MySql database name not found'));
                    die;
                }
                //user
                if(isset($mySql['user'])){
                    $user= $mySql['user'];
                }else{
                    echo json_encode(array('status'=>996, 'errorMessage'=> 'MySql user name not found'));
                    die;
                }
                //password
                if(isset($mySql['password'])){
                    $password= $mySql['password'];
                }else{
                    echo json_encode(array('status'=>995, 'errorMessage'=> 'MySql invalid password'));
                    die;
                }
                //open connection
                $connection = mysqli_connect($server, $user, $password, $database);
                //error in connection 
                if($connection===false){
                    echo json_encode(array('status'=> 994, 'errorMessage'=>'Could not connect to MySql'));
                    die;
                }
                //character set
                $connection->set_charset('utf8');
                //return connection
                return $connection;

                
            }else{
                echo json_encode(array('status'=>999, 'errorMessage'=> 'Invalid configuration file'));
                die;
            }
        }
    }
?>