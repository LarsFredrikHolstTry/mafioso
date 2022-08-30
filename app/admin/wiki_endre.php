<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_GET['wiki_edit_id'])) {

    $wiki_id = $_GET['wiki_edit_id'];

    $query = $pdo->prepare("SELECT * FROM wiki WHERE WIKI_ID = ?");
    $query->execute(array($wiki_id));
    $WIKI_row = $query->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['edit'])) {
        $title = htmlspecialchars($_POST['title']);
        $text = $_POST['text'];

        $date = time();

        $sql = "UPDATE wiki SET WIKI_title=?, WIKI_desc=?, WIKI_date=? WHERE WIKI_ID=?";
        $pdo->prepare($sql)->execute([$title, $text, $date, $wiki_id]);

        echo feedback("Ny wiki post er oppdatert!", "success");
    }


?>

    <div class="content">
        <h4>Rediger wki</h4>
        <form method="post">
            <p>Tittel</p>
            <input name="title" value="<?php echo $WIKI_row['WIKI_title']; ?>" type="text">
            <p>Tekst</p>
            <textarea name="text" rows="20"><?php echo $WIKI_row['WIKI_desc']; ?></textarea>
            <input type="submit" name="edit" value="endre">
        </form>
    </div>


<?php

} else {
    echo feedback("Velg wiki via wiki-siden", "blue");
}

?>