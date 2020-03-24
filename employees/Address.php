<?php


namespace Employees;

/***
 * Class Address
 * @package Employees
 *
 */
class Address
{
    /** @var string $street */
    public $street;
    /** @var string $city */
    public $city;
    /** @var string $postCode */
    public $postCode;

    /**
     * Address constructor.
     *
     * @param string $street
     * @param string $city
     * @param string $postCode
     */
    public function __construct($street = "", $city = "", $postCode = "")
    {
        $this->street = $street;
        $this->city = $city;
        $this->postCode = $postCode;
    }
}