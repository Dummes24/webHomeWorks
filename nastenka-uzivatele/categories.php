<?php
//načteme připojení k databázi a inicializujeme session
require_once 'inc/user.php';

if (empty($_SESSION['user_id']) || @$_SESSION['admin'] != 1) {
    //uživatel není přihlášen
    include 'inc/header.php';
    echo '<h3 style="margin:auto;">Pro úpravu kategorií na nástěnce musíte být přihlášen(a) jako administrátor.</h3>';
    echo 'Budete přesměrován na hlavní stránku';
    header('refresh:3;url=index.php');
    include 'inc/footer.php';
    exit();
}

include 'inc/header.php';

#region formulář s výběrem kategorií
echo '<form method="get" id="categoryFilterForm">
  <label for="category">Kategorie:</label>
  <select name="category" id="category" onchange="document.getElementById(\'categoryFilterForm\').submit();">';
echo '<option value=""';
if (empty(@$_GET['category'])) {
    echo ' selected';
}
echo ' disabled>Vyberte kategorii</option>';
$categories = $db->query('SELECT * FROM categories ORDER BY name;')->fetchAll(PDO::FETCH_ASSOC);
if (!empty($categories)) {
    foreach ($categories as $category) {
        echo '<option value="' . $category['category_id'] . '"';//u category_id nemusí být ošetření speciálních znaků, protože jde o číslo
        if ($category['category_id'] == @$_GET['category']) {
            echo ' selected="selected" ';
            $nameToChange = $category['name'];
        }
        echo '>' . htmlspecialchars($category['name']) . '</option>';
    }
}

echo '  </select>';
echo '</form>';
#endregion formulář s výběrem kategorií
?>

<table>
    <?php
    $categories = $db->query('SELECT * FROM categories ORDER BY name;')->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($categories)) {
        foreach ($categories as $category) {
            echo '<tr>';
            echo '<td>';
            echo $category['name'];
            echo '</td>';
            echo '</tr>';
        }
    }
    ?>
</table>


<?php
//vložíme do stránek patičku
include 'inc/footer.php';
