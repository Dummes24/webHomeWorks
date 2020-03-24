<?php namespace Employees;
/**
 * Form for adding new employees to file.
 */
?>

<form method="post" name="visitorsBook">
    <label for="firstName">Jméno</label>
    <?php
    if (!empty($errors["firstName"])) {
        echo "<span class='invalidInput'>" . $errors["firstName"] . "</span>";
    }
    ?>
    <span class="inputPadding"><input type="text" name="firstName" id="firstName" required/></span>

    <label for="secondName">Příjmení</label>
    <?php
    if (!empty($errors["secondName"])) {
        echo "<span class='invalidInput'>" . $errors["secondName"] . "</span>";
    }
    ?>
    <span class="inputPadding"><input type="text" name="secondName" id="secondName" required/></span>

    <label for="gender">Pohlaví</label>
    <?php
    if (!empty($errors["gender"])) {
        echo "<span class='invalidInput'>" . $errors["gender"] . "</span>";
    }
    ?>
    <span class="inputPadding">
                    <select name="gender" id="gender">
                        <option value="M">Muž</option>
                        <option value="Ž">Žena</option>
                    </select>
                </span>

    <label for="street">Ulice</label>
    <span class="inputPadding"><input type="text" name="street" id="street"/></span>

    <label for="city">Obec</label>
    <?php
    if (!empty($errors["city"])) {
        echo "<span class='invalidInput'>" . $errors["city"] . "</span>";
    }
    ?>
    <span class="inputPadding"><input type="text" name="city" id="city" required/></span>

    <label for="postCode">PSČ</label>
    <?php
    if (!empty($errors["postCode"])) {
        echo "<span class='invalidInput'>" . $errors["postCode"] . "</span>";
    }
    ?>
    <span class="inputPadding">
        <input type="text" name="postCode" id="postCode" required
               pattern="[1-7][0-9]{2} ?[0-9]{2}"
        />
    </span>

    <label for="phone">Telefonní číslo</label>
    <?php
    if (!empty($errors["phone"])) {
        echo "<span class='invalidInput'>" . $errors["phone"] . "</span>";
    }
    ?>
    <span class="inputPadding"><input type="text" name="phone" id="phone"
                                      pattern="(\+[0-9]{3})?[0-9]{3}[ -]?[0-9]{3}[ -]?[0-9]{3}?"
                                      title="Ve formátu +420 123 456 789"/>
                </span>

    <label for="email">E-mail adresa</label>
    <?php
    if (!empty($errors["email"])) {
        echo "<span class='invalidInput'>" . $errors["email"] . "</span>";
    }
    ?>
    <span class="inputPadding"><input type="email" name="email" id="email"/></span>

    <label for="position">Pozice</label>
    <?php
    if (!empty($errors["position"])) {
        echo "<span class='invalidInput'>" . $errors["position"] . "</span>";
    }
    ?>
    <script>
        function displaySupervisor(answer) {
            if (answer === "dělník") {
                document.getElementById('supervisorInput').style.display = "block";
            } else {
                document.getElementById('supervisorInput').style.display = "none";
                document.getElementById('supervisor').value = "";
            }
        }
    </script>
    <span class="inputPadding">
                    <select name="position" id="position" onchange="displaySupervisor(this.value)">
                        <option value="mistr">Mistr</option>
                        <option value="dělník">Dělník</option>
                    </select>
                </span>

    <div id="supervisorInput">
        <label for="supervisor">Nadřízený</label>
        <?php
        if (!empty($errors["supervisor"])) {
            echo "<span class='invalidInput'>" . $errors["supervisor"] . "</span>";
        }
        ?>
        <span class="inputPadding">
                    <select id="supervisor" name="supervisor" title="Přiřaď nadřízenou osobu">
                        <option disabled selected value="">Vyberte nadřízeného</option>
                        <?php
                        //TODO Refactor formAddEmployee to not need this warning suppression
                        /** @noinspection PhpUndefinedVariableInspection */
                        foreach ($employees->supervisors as $supervisor) {
                            echo '<option value="' . htmlspecialchars($supervisor) . '">' . htmlspecialchars($supervisor) . "</option>\n";
                        }
                        ?>
                        <!--<option value="foo">Foo</option>-->
                    </select>
                </span>
    </div>

    <span class="inputPadding"><input id="formButton" type="submit" value="Přidat zaměstnance"/></span>
</form>