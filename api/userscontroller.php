    <?php
    //require files
    require_once('models/user.php');
    //require_once('config/exceptions/recordnotfoundexception.php');

    //get
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($parameters == '') {
            //get all
            if ($action == '') {
                // authorazation
                if (User::belongsToRole($headers['username'], ['SA', 'SUPER'])) {
                    echo json_encode(array(
                        'status' => 0,
                        'users' => json_decode(User::getAllToJson())
                    ));
                } else echo json_encode(array('status' => 502, 'errorMessage' => 'User does not have access to this module'));
            }
            // login
            if ($action == 'login') {
                // read headers
                $headers = getallheaders();
                // verify headers
                if (isset($headers['username']) && isset($headers['password'])) {
                    try {
                        $u = new User($headers['username'], $headers['password']);
                        echo json_encode(array('status' => 0, 'user' => json_decode($u->toJsonFull())));
                    } catch (AccessDeniedException $ex) {
                        echo json_encode(array('status' => 500, 'errorMessage' => $ex->getMessage()));
                    }
                } else
                    echo json_encode(array('status' => 501, 'errorMessage' => 'Missing security headers'));
            }
        } else {
            //get one
            try {
                $d = new User($parameters);
                //display object
                echo json_encode(array(
                    'status' => 0,
                    'user' => json_decode($d->toJsonFull())
                ));
            } catch (RecordNotFoundException $ex) {
                echo json_encode(array('status' => 1, 'errorMessage' => $ex->getMessage()));
            }
        }
    }
