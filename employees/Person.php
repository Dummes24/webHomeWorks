<?php


namespace Employees;

/***
 * Class Person - Hold data about people
 * @package lessons
 *
 * @property string $firstName
 * @property string $secondName
 * @property string $phoneNumber
 * @property string $email
 * @property string $gender
 */
class Person
{
    /** @var string $firstName */
    public $firstName;
    /** @var string $secondName */
    public $secondName;
    /** @var string $phoneNumber */
    public $phoneNumber;
    /** @var string $email */
    public $email;
    /** @var string $gender */
    public $gender;

    /**
     * Person constructor.
     * @param string $firstName
     * @param string $secondName
     * @param string $phoneNumber
     * @param string $email
     * @param string $gender
     */
    public function __construct($firstName = "", $secondName = "", $phoneNumber = "", $email = "", $gender = "")
    {
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->gender = $gender;
    }
}