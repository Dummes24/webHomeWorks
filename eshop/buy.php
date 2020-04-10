<?php
session_start();

require 'db.php';

#region vytvoření session pole pro košík
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
#endregion vytvoření session pole pro košík

#region kontrola, jestli je zboží v DB
$stmt = $db->prepare("SELECT * FROM goods WHERE id=?");
$stmt->execute(array($_GET['id']));
$goods = $stmt->fetch();

if (!$goods) {
    header('Location: cart.php?found=False');
    die("Unable to find goods!");
}
#endregion kontrola, jestli je zboží v DB
//Find if bought item already in cart
foreach ($_SESSION['cart'] as &$sItem) {
    $item = unserialize($sItem);
    if ($item['id'] == $goods["id"]) {
        $item['count'] += 1;
        $sItem = serialize($item);
        $found = true;
        break;
    }
}
//If not found appen to cart array
if (!isset($found)) {
    $_SESSION['cart'][] = serialize(["id" => $goods["id"], "count" => 1]);//přidání ID zboží do košíku
}


header('Location: cart.php');//přesměrujeme uživatele na košík
