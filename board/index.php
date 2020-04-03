<?php
//načteme připojení k databázi
require_once 'inc/db.php';

//vložíme do stránek hlavičku
include 'inc/header.php';
?>
    <form method="post">
        <div class="form-group">
            <label for="category">Filtrovat dle kategorie</label>
            <select name="category" id="category" required
                    class="form-control <?php echo(!empty($errors['category']) ? 'is-invalid' : ''); ?>">
                <option value="0">Všechny kategorie</option>
                <?php
                $categoryQuery = $db->prepare('SELECT * FROM categories ORDER BY name;');
                $categoryQuery->execute();
                $categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($categories)) {
                    $categoryFound = False;
                    foreach ($categories as $category) {
                        echo '<option value="' . $category['category_id'] . '" ' . ($category['category_id'] == @$_POST['category'] ? 'selected="selected"' : '') . '>' . htmlspecialchars($category['name']) . '</option>';

                        //"Validating" posted category
                        if ($category['category_id'] == @$_POST['category']) {
                            $categoryFound = True;
                        }
                    }
                }
                ?>
            </select>
            <br>
            <button type="submit" class="btn btn-primary">Filtrovat</button>
            <?php
            if (!$categoryFound) {
                echo '<div class="invalid-feedback">' . 'Kategorie nenalezena' . '</div>';
            }
            ?>
        </div>
    </form>
<?php
$query = $db->prepare('SELECT
                           posts.*, users.name AS user_name, users.email, categories.name AS category_name
                           FROM posts JOIN users USING (user_id) JOIN categories USING (category_id) ORDER BY updated DESC;');
$query->execute();

$posts = $query->fetchAll(PDO::FETCH_ASSOC);
/*echo "<pre>";
var_dump($posts);
echo "</pre>";*/
if (!empty($posts)) {
    #region výpis příspěvků

    echo '<div class="row">';
    foreach ($posts as $post) {
        if (!$categoryFound || $post['category_id'] == @$_POST['category']) {
            echo '<article class="col-12 col-md-6 col-lg-4 col-xxl-3 border border-dark mx-1 my-1 px-2 py-1">';
            echo '  <div><span class="badge badge-secondary">' . htmlspecialchars($post['category_name']) . '</span></div>';
            echo '  <div>' . nl2br(htmlspecialchars($post['text'])) . '</div>';
            echo '  <div class="small text-muted mt-1">';
            echo htmlspecialchars($post['user_name']);
            echo ' ';
            echo date('d.m.Y H:i:s', strtotime($post['updated']));//datum získané z databáze převedeme na timestamp a ten pak do českého tvaru
            echo '  </div>';
            echo '<a href="edit.php?postId=' . $post['post_id'] . '" class="btn btn-primary">Upravit příspěvek</a>';
            echo '</article>';
        }
    }
    echo '</div>';
    #endregion výpis příspěvků
} else {
    echo '<div class="alert alert-info">Nebyly nalezeny žádné příspěvky.</div>';
}

echo '<div class="row my-3">
          <a href="edit.php" class="btn btn-primary">Přidat příspěvek</a>
        </div>';

//vložíme do stránek patičku
include 'inc/footer.php';