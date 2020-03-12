<?php


namespace lessons;

/***
 * Class Person - Hold data about people
 * @package lessons
 *
 * @property string $firstName
 * @property string $secondName
 * @property string $phoneNumber
 * @property string $email
 */
class Person
{
    public $firstName;
    public $secondName;
    public $phoneNumber;
    public $email;

    public function __construct($firstName = "", $secondName = "", $phoneNumber = "", $email = "")
    {
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
    }
}