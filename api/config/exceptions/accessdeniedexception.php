<?php
class AccessDeniedException extends Exception
{
    //attributes
    protected $message;

    //constructor
    public function __construct()
    {
        //get arguments
        $arguments = func_get_args();
        //0 arguments : generic message
        if (func_num_args() == 0) {
            $this->message = 'Access denied';
        }
        //1 arguments : specific message
        if (func_num_args() == 1) {
            $this->message = 'Access denied for user ' . $arguments[0];
        }
    }
}
