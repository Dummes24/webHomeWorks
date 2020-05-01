<?php
//připojení k databázi
require 'db.php';

//přístup jen pro admina
require 'admin_required.php';

#region načtení zboží k aktualizaci
$stmt = $db->prepare('SELECT * FROM goods WHERE id=:id');
$stmt->execute([':id' => @$_REQUEST['id']]);
$goods = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$goods) {
    //pokud zboží neexistuje (např. bylo mezitím smazáno), nepokračujeme dál - i když chyba by určitě mohla být vypsána hezčeji :)
    die("Unable to find goods!");
}

$name = $goods['name'];
$description = $goods['description'];
$price = $goods['price'];
#endregion načtení zboží k aktualizaci

if (!empty($_POST)) {
    $formErrors = '';

    //DONE tady by měly být nějaké kontroly odeslaných dat, že :)

    //Kontroly ;)
    if (!isset($_POST['name']) || trim($_POST['name']) == "") {
        $errors['name'] = "Musíte stanovit název zboží";
    }

    if (!isset($_POST['description']) || trim($_POST['description']) == "") {
        $errors['description'] = "Musíte stanovit popis zboží";
    }

    if (!isset($_POST['price']) || !is_numeric($_POST['price']) || (float)$_POST['price'] <= 0) {
        $errors['price'] = "Musíte stanovit cenu zboží větší než 0";
    }


    $newName = $name;
    $newDescription = $description;
    $newPrice = $price;

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);

    /*
     * OPTIMISTIC LOCK:
     * Před uložením si vytáhneme z DB čas poslední změny. Pokud se tento liší od času předaného z formuláře (tj. času na začátku editace), znamená to, že se záznam mezitím v pozadí změnil. (Jiný uživatel provedl update. Mohl to být ale i ten samý uživatel např. v jiném okně prohlížeče.)
     * V případě, že se záznam v mezičase změnil, je nutné se NĚJAK zachovat. Je možné uživatele varovat, nabídnout přeuložení, sloučení změn atd., nebo prostě jen umřít s hláškou, že záznam byl změněn.
     *
     * Proměnnou $_POST['last_updated_at'] si předáváme ve formuláři jako hidden pole.
     */

    if ($_POST['last_updated_at'] != $goods['last_updated_at']) {
        //tady by měl ideálně být návrat na formulář s označenými daty, co se změnilo, a nabídnout přepis či ponechání dat
        //DONE domácí úkol :)
        $formErrors = 'Zboží bylo změněno jiným uživatelem';
        //exit("The goods were updated by someone else in meantime!");
    }

    //pokud se časy poslední editace záznam a čas z formuláře rovnají, tj. záznam nebyl mezitím změněn, můžeme provést update - tedy pokud formulář neobsahuje jiné chyby
    //nakonec také zaktualizujeme čas poslední aktualizace uložený u daného zboží
    if (empty($formErrors) && !isset($errors)) {
        #region uložení zboží do DB
        $stmt = $db->prepare('UPDATE goods SET name=:name, description=:description, price=:price, last_updated_at=now() WHERE id=:id LIMIT 1;');
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':id' => $goods['id']
        ]);
        #endregion uložení zboží do DB

        //přesměrování na homepage
        header('Location: index.php');
        exit();
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>PHP Shopping App</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<?php include 'navbar.php' ?>

<h1>Update goods</h1>

<?php
if (!empty($formErrors)) {
    echo '<p style="color:red;">' . $formErrors . '</p>';
}
?>

<form method="post">
    <label for="name">Name</label><br/>
    <?php
    if (!empty($formErrors) && $name != @$newName) {
        echo '<label for="description" style="color: darkred">Upravená hodnota: ' . @$newName . '</label><br/>';
    }
    if (isset($errors) && !empty(@$errors['name'])) {
        echo '<p style="color: red">' . $errors['name'] . '</p>';
    }
    ?>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars(@$name); ?>" required><br/><br/>

    <label for="price">Price<br/>
        <?php
        if (!empty($formErrors) && $price != @$newPrice) {
            echo '<label for="description" style="color: darkred">Upravená hodnota: ' . @$newPrice . '</label><br/>';
        }
        if (isset($errors) && !empty(@$errors['price'])) {
            echo '<p style="color: red">' . $errors['price'] . '</p>';
        }
        ?>
        <input type="number" min="0" name="price" id="price" required
               value="<?php echo htmlspecialchars(@$price) ?>"><br/><br/>

        <label for="description">Description</label><br/>
        <?php
        if (!empty($formErrors) && $description != @$newDescription) {
            echo '<label for="description" style="color: darkred">Upravená hodnota: ' . @$newDescription . '</label><br/>';
        }
        if (isset($errors) && !empty(@$errors['description'])) {
            echo '<p style="color: red">' . $errors['description'] . '</p>';
        }
        ?>
        <textarea name="description" id="description"><?php echo htmlspecialchars(@$description) ?></textarea><br/><br/>

        <br/>

        <input type="hidden" name="id" value="<?php echo $goods['id']; ?>"/>

        <input type="hidden" name="last_updated_at" value="<?php echo $goods['last_updated_at']; ?>">
        <!--hidden pole používáme pro předání informace o čase poslední změny záznamu-->
        <?php
        if (!empty($formErrors)) {
            echo '<input type="submit" value="Přepsat" /> or <a href="index.php">Cancel</a>';
        } else {
            echo '<input type="submit" value="Save" /> or <a href="index.php">Cancel</a>';
        }
        ?>
</form>

</body>
</html>
