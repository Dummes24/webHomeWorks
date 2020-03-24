<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seznam zaměstnanců</title>
    <link rel="stylesheet" type="text/css" href="../baseCss.css"/>
    <link rel="stylesheet" type="text/css" href="collapsible.css"/>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <script src="https://www.w3schools.com/lib/w3.js"></script>
</head>
<body>


<!--Collapsible div based on https://alligator.io/css/collapsible/-->
<!--licenced under MIT, edited-->
<div class="wrap-collabsible">
    <input id="collapsible" class="toggle" type="checkbox"
        <?php

        use Employees\EmployeeLoader;
        use function Employees\validateEmployeePOST;

        include_once "validateAddEmployee.php";
        include_once "EmployeeLoader.php";
        $employees = new EmployeeLoader();
        $errors = validateEmployeePOST($employees->supervisors);
        if (!empty($errors)) {
            echo "checked";
        }
        ?>
    >
    <label for="collapsible" class="lbl-toggle">Přidat zaměstnance</label>
    <div class="collapsible-content">
        <div class="content-inner">
            <?php include "formAddEmployee.php" ?>
        </div>
    </div>
</div>

<table id="employees">
    <?php
    //Add header from loadEmployees to array
    echo "<tr style='font-weight: bold'>\n";
    $fieldNum = 1;
    foreach ($employees->header as $headerItem) {
        echo "\t<th onclick=\"w3.sortHTML('#employees','.item', 'td:nth-child(" . $fieldNum . ")')\">"
            . $headerItem .
            " <span style='color:grey'>▲ ▼</span></th>\n";
        $fieldNum++;
    }
    echo "</tr>";

    //Add all employees from loadEmployees
    foreach ($employees->employeeArray as $employee) {
        echo "<tr class=\"item\">\n";
        foreach ($employee->returnFieldsArray() as $field) {
            echo "\t<td>" . htmlspecialchars($field) . "</td>\n";
        }
        echo "</tr>\n";
    }
    ?>
</table>
</body>
</html>