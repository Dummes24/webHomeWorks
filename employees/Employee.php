<?php

namespace Employees;

include "Person.php";
include "Address.php";

/**
 * Class Employee
 * @package Employees
 */
class Employee extends Person
{
    /** @var Address $address */
    public $address;
    /** @var string $position */
    public $position;
    /** @var string $supervisor */
    public $supervisor;
    /** @var string $gender */
    public $gender;

    /**
     * Employee constructor.
     * @param string $firstName
     * @param string $secondName
     * @param string $gender
     * @param string $street
     * @param string $city
     * @param string $postCode
     * @param string $phoneNumber
     * @param string $email
     * @param string $position
     * @param string $supervisor firstName . " " . secondName
     */
    public function __construct($firstName = "", $secondName = "", $gender = "",
                                $street = "", $city = "", $postCode = "",
                                $phoneNumber = "", $email = "",
                                $position = "", $supervisor = "")
    {
        parent::__construct($firstName, $secondName, $phoneNumber, $email, $gender);
        $this->address = new Address($street, $city, $postCode);
        $this->position = $position;
        $this->supervisor = $supervisor;
        $this->gender = $gender;
    }

    public function returnFieldsArray()
    {
        return array($this->firstName, $this->secondName, $this->gender,
            $this->address->street, $this->address->city, $this->address->postCode,
            $this->phoneNumber, $this->email,
            $this->position, $this->supervisor);

    }
}