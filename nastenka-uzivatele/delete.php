<?php

//načteme připojení k databázi a inicializujeme session
require_once 'inc/user.php';

if (empty($_SESSION['user_id'])) {
    //uživatel není přihlášen
    include 'inc/header.php';
    echo '<h3 style="margin:auto;">Pro mazání příspěvků na nástěnce musíte být přihlášen(a).</h3>';
    echo 'Budete přesměrován na hlavní stránku';
    header('refresh:5;url=index.php');
    include 'inc/footer.php';
    exit();
}

//pomocné proměnné pro přípravu dat do formuláře
$postId = '';
$postCategory = (!empty($_REQUEST['category']) ? intval($_REQUEST['category']) : '');
$postText = '';

#region načtení existujícího příspěvku z DB
if (!empty($_REQUEST['id'])) {
    $postQuery = $db->prepare('SELECT * FROM posts WHERE post_id=:id LIMIT 1;');
    $postQuery->execute([':id' => $_REQUEST['id']]);
    if ($post = $postQuery->fetch(PDO::FETCH_ASSOC)) {
        //naplníme pomocné proměnné daty příspěvku
        $postId = $post['post_id'];
        $postCategory = $post['category_id'];
        $postText = $post['text'];

        //Kontrola uživatele
        if ($post['user_id'] != $_SESSION['user_id'] && $_SESSION['admin'] != 1) {
            include 'inc/header.php';
            echo '<h3 style="margin:auto;">Můžete mazat pouze své příspěvky</h3>';
            echo 'Budete přesměrován na hlavní stránku';
            header('refresh:5;url=index.php');
            include 'inc/footer.php';
            exit();
        }

    } else {
        include 'inc/header.php';
        echo '<h3 style="margin:auto;">Příspěvek neexistuje.</h3>';
        echo 'Budete přesměrován na hlavní stránku';
        header('refresh:5;url=index.php');
        include 'inc/footer.php';
        exit();
    }
}
#endregion načtení existujícího příspěvku z DB

//Existuje => smažeme z databáze a přesměrujeme zpět na index
$postQuery = $db->prepare('DELETE FROM posts WHERE post_id=:id LIMIT 1');
$postQuery->execute([':id' => $_REQUEST['id']]);
header('Location: index.php');
