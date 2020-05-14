<?php
    class RecordNotFountException extends Exception {
        // attributes
        protected $message;

        // contructor
        public function __construct() {
            // get arguments
            $arguments = func_get_args();
            // 0 arguments : generic message
            if (func_num_args() == 0) $this->message = 'Record not found';
            // 1 arguments : specific message
            if (func_num_args() == 1) $this->message = 'Record not found for id '.$arguments[0];
        }
    }
?>