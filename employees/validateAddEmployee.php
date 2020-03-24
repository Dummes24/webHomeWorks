<?php
namespace Employees;

/**
 * Checks if page was updated with POST, and validates data
 *
 * @param array $supervisors Array containing concatenated firstName secondName, of "mistr" employees
 *
 * @return array Array containing error strings, if empty no error occured
 */

function validateEmployeePOST($supervisors)
{
    $errors = [];

    if (!empty($_POST)) {
        //Validace dat
        if (empty($_POST['firstName'])) {
            $errors['firstName'] = 'Špatně zadané jméno';
        }

        if (empty($_POST['secondName'])) {
            $errors['secondName'] = 'Špatně zadané příjmení';
        }

        if (empty($_POST['gender']) || !($_POST['gender'] === 'M' || $_POST['gender'] === 'Ž')) {
            $errors['gender'] = 'Nevalidní pohlaví';
        }

        if (empty($_POST['city'])) {
            $errors['city'] = 'Špatně zadaná obec';
        }

        if (empty($_POST['postCode'])) {
            $errors['postCode'] = 'Musíte zadat PSČ';
        } else {

            $_POST['postCode'] = trim($_POST['postCode']);
            if (!preg_match('/^[1-7][0-9]{2} ?[0-9]{2}/', trim($_POST['postCode']))) {
                $errors['postCode'] = 'Špatně zadané PSČ';
            }
        }

        //Source: https://github.com/4iz278/cviceni/blob/c1cb132b47b077ea95d3ac7ac02692dee641cb24/04-objekty-II-validace/04-priklad-validace/formular.php#L16
        //Don't reinvent the wheel ^^
        if (!empty($_POST['phone'])) {
            //odstraníme z telefonu nadbytečné znaky
            $_POST['phone'] = str_replace([' ', '-', '/'], ['', '', ''], $_POST['phone']);
            //kontrola regulárním výrazem - chceme jen česká čísla
            if (!preg_match('/^(\+420)?[0-9]{9}$/', $_POST['phone'])) {
                $errors['phone'] = 'Musíte zadat platné české telefonní číslo, nebo ponechte toto pole prázdné.';
            }
        }
        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Musíte zadat platný e-mail.';
        }

        if (empty($_POST['position'])) {
            $errors['position'] = 'Špatně vybraná pozice' . ' ' . $_POST['position'];
        } elseif ($_POST['position'] === 'dělník' &&
            (empty($_POST["supervisor"]) || !isInSupervisors($_POST['supervisor'], $supervisors))
        ) {
            $errors["supervisor"] = "Musíte vybrat existujícího nadřízeného";
        }

        if (empty($errors)) {
            $data = [
                $_POST["firstName"],
                $_POST["secondName"],
                $_POST["gender"],
                (!empty($_POST["street"]) ? $_POST["street"] : ""),
                $_POST["city"],
                $_POST["postCode"],
                (!empty($_POST["phone"]) ? $_POST["phone"] : ""),
                (!empty($_POST["email"]) ? $_POST["email"] : ""),
                (!empty($_POST["position"]) ? $_POST["position"] : ""),
                (!empty($_POST["supervisor"]) ? $_POST["supervisor"] : "")
            ];

            $file = fopen("adresar.csv", 'a');
            if ($file !== FALSE) {
                fputcsv($file, $data, ';');
                fclose($file);
                header("Location: index.php");
            }
        }
    }

    return $errors;
}

function isInSupervisors($employeeName, $supervisors)
{
    foreach ($supervisors as $supervisor) {
        if ($employeeName === $supervisor) {
            return TRUE;
        }
    }
    return FALSE;
}
