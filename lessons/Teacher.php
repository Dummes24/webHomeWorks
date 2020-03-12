<?php


namespace lessons;

class Teacher extends Person
{

    public function __construct($firstName = "", $secondName = "", $phoneNumber = "", $email = "")
    {
        parent::__construct($firstName, $secondName, $phoneNumber, $email);
    }
}