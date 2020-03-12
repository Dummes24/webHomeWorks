<?php


namespace lessons;

class Student extends Person
{

    public function __construct($firstName = "", $secondName = "", $phoneNumber = "", $email = "")
    {
        parent::__construct($firstName, $secondName, $phoneNumber, $email);
    }
}