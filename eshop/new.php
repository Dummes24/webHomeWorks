<?php

require 'db.php';//připojení k databázi

if ($_SERVER["REQUEST_METHOD"] == "POST") {//kontrolujeme, jestli byl požadavek poslán metodou POST

    //Kontroly ;)
    if (!isset($_POST['name']) || trim($_POST['name']) == "") {
        $errors['name'] = "Goods name must be set";
    }

    if (!isset($_POST['description']) || trim($_POST['description']) == "") {
        $errors['description'] = "Goods description must be set";
    }

    if (!isset($_POST['price']) || !is_numeric($_POST['price']) || (float)$_POST['price'] <= 0) {
        $errors['name'] = "Goods price must be set and above 0";
    }

    if (!isset($errors)) {
        $stmt = $db->prepare("INSERT INTO goods(name, description, price) VALUES (?, ?, ?)");//prepared statement pro uložení dat (tentokrát s anonymními parametry)
        $stmt->execute(array($_POST['name'], $_POST['description'], (float)$_POST['price']));//naplnění předchozího statementu daty

        header('Location: index.php');//přesměrujeme uživatele na homepage (při přesměrování už se nic dalšího nevykreslí)
    } else {
        header('Location: index.php?errors=' . urlencode(serialize($errors)));
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>New goods - PHP Shopping App</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<?php include 'navbar.php' ?>

<h1>New goods</h1>

<form method="post"><!--formulář pro zadání zboží do e-shopu - pro zjednodušení bez formátovacích značek-->
    <label for="name">Name</label><br/>
    <input type="text" name="name" id="name" value="" required><br/><br/>

    <label for="price">Price</label><br/>
    <input type="number" name="price" id="price" value=""><br/><br/>

    <label for="description">Description</label><br/>
    <textarea name="description" id="description"></textarea><br/><br/>

    <input type="submit" value="Save"> or <a href="./">Cancel</a>
    <!--kromě odesílacího tlačítka je vhodné umožnit také odejít z formuláře bez jeho odeslání-->
</form>

</body>
</html>
