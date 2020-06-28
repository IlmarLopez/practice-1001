<?php
class UserRole
{
    //attributes
    private $name;

    public function getName()
    {
        return $this->name;
    }
    //constructor
    public function __construct($name)
    {
        $this->name = $name;
    }

    //represent the object as a JSON object
    public function toJson()
    {
        return json_encode(array(
            'name' => $this->name,
        ));
    }
}
