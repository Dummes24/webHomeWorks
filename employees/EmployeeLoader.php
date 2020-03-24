<?php


namespace Employees;

include_once "Employee.php";

class EmployeeLoader
{
    /** @var array $header Table header loaded from file */
    public $header = [];
    /** @var array $employeeArray Array of Employee objects loaded from file */
    public $employeeArray = [];
    /** @var array $supervisors Array containing concatenated firstName secondName, of "mistr" employees */
    public $supervisors = [];
    /** @var string $fileHandle Path to readable .csv file containing saved employees */
    private $fileHandle;

    /**
     * EmployeeLoader constructor.
     *
     * @param string $fileHandle Path to readable .csv file containing saved employees
     */
    public function __construct($fileHandle = __DIR__ . "/adresar.csv")
    {
        $this->fileHandle = $fileHandle;
        $this->loadEmployees();
    }

    /**
     * Loads employees, header and supervisors from $this->file
     */
    public function loadEmployees()
    {
        $rowNum = 1;
        if (($handle = fopen($this->fileHandle, "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 0, ';')) !== FALSE) {
                if ($rowNum !== 1) {
                    /*$employeeArray[] = new \Employees\Employee($row[0], $row[1], $row[2],
                                                               $row[3], $row[4], $row[5],
                                                               $row[6], $row[7],
                                                               $row[8], $row[9]);*/
                    $this->employeeArray[] = new Employee(...$row);
                } else {
                    //Load header
                    $this->header = $row;
                }

                $rowNum++;
            }
        }

        foreach ($this->employeeArray as $employee) {
            if ($employee->position === "mistr") {
                $this->supervisors[] = $employee->firstName . " " . $employee->secondName;
            }
        }
    }
}