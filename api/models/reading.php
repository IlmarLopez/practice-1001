<?php
    class Reading{
        //attributes
        private $dateTime;
        private $value;

        public function getDateTime(){
            return $this->dateTime;
        }
        public function getValue(){
            return $this->value;
        }
        //constructor
        public function __construct($dateTime, $value){
                $this->dateTime=$dateTime;
                $this->value=$value;    
        }

        //represent the object as a JSON object
        public function toJson(){
            return json_encode(array(
                'dateTime'=>$this->dateTime,
                'value'=>$this->value
            ));
        }
    }
?>