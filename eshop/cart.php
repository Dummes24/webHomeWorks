<?php

session_start();//nastartujeme session
require 'db.php';//načteme připojení k databázi

$cart = @$_SESSION['cart'];//načteme IDčka a počty zboží, které máme v košíku (pročpak je tu asi ten zavináč :-))
if (is_array($cart) && count($cart) > 0) {
    //Getting all ids
    foreach ($cart as $item) {
        $ids[] = unserialize($item)["id"];
    }

    //vygenerujeme si řetězec s otazníky o takové délce, kolik máme kusů zboží v košíku;pokud mam treba v ids 1,2,3, vrati mi ?,?,?
    $question_marks = str_repeat('?,', count($ids) - 1) . '?';

    //připravíme si prepared statement pro načtení zboží podle jeho IDček
    $stmt = $db->prepare("SELECT * FROM goods WHERE id IN ($question_marks) ORDER BY name");
    //naplníme statement IDčky; funkce array_values vrací hodnoty z pole bez ohledu na jeho původní indexy (výsledkem je pole normálně indexované od 0, což je vyžadováno pro naplnění nepojmenovaných parametrů označených ?)
    $stmt->execute(array_values($ids));
    //všechny produkty načteme
    $goods = $stmt->fetchAll();
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>My shopping cart - PHP Shopping App</title>
    <link rel="stylesheet" type="text/css" href="./styles.css">
</head>
<body>

<?php include 'navbar.php' ?>

<h1>My shopping cart</h1>

Total goods selected: <strong><?php echo(!empty($goods) ? count($goods) : '0'); ?></strong>

<br/><br/>

<a href="index.php">Back to the goods</a>
<?php
if (isset($_GET['found']) && $_GET['found'] == 'False') {
    echo '<h3 color="red">Your desired goods were not found!</h3>';
}
?>
<h3></h3>
<br/><br/>

<?php
if (!empty($goods)) {
    #region výpis zboží v košíku
    //tentokrát vypisujeme tabulku rovnou z PHP, ale určitě by šlo stejně jako např. na index.php výpis PHP přerušit a vložit do něj HTML
    //sami si vyberte, která varianta vám přijde přehlednější :)
    $sum = 0;
    echo '<table>
                <thead>
                  <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Count</th>
                    <th>Price</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>';
    #region výpis jednotlivých položek v košíku
    foreach ($goods as $good) {

        //Find count of current item
        foreach ($cart as $serialItem) {
            $item = unserialize($serialItem);
            if ($item["id"] == $good['id']) {
                $count = $item['count'];
                break;
            }
        }

        echo '  <tr>
                    <td class="center">
                      <a href="remove.php?id=' . $good['id'] . '">Remove</a>
                    </td>
                    <td>' . htmlspecialchars($good['name']) . '</td>
                    <td>' . $count . '</td>
                    <td class="right">' . $good['price'] . '</td>
                    <td>' . htmlspecialchars($good['description']) . '</td>
                  </tr>';
        $sum += $good['price'] * $count;
    }
    #endregion výpis jednotlivých položek v košíku
    echo '  </tbody>
                <tfoot>
                  <tr>
                    <td>SUM</td>
                    <td></td>                     
                    <td class="right">' . $sum . '</td>
                    <td></td>
                  </tr>
                </tfoot>                
              </table>';
    #endregion výpis zboží v košíku
} else {
    echo 'No goods yet.';
}
/*
echo "<pre>";
@var_dump($_SESSION['cart']);
echo "</pre>";
*/
?>
</body>
</html>