<?php

if (!isset($_GET['side'])) {
    die();
}

if (!isset($_GET['side'])) {
    die();
}

if (isset($_GET['wiki_delete_id'])) {

    $wiki_delete_id = $_GET['wiki_delete_id'];

    $query = $pdo->prepare("SELECT * FROM wiki WHERE WIKI_ID = ?");
    $query->execute(array($wiki_delete_id));
    $WIKI_row = $query->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM wiki WHERE WIKI_ID = $wiki_delete_id";
        $pdo->exec($sql);

        header("Location: ?side=wiki");
    }

?>
    <div class="content">
        <div class="header">
            <span><img class="header_icon" src="img/icons/information.png"><?php echo $WIKI_row['WIKI_title']; ?></span>
        </div>
        <div class="pad_10">
            <?php echo $WIKI_row['WIKI_desc']; ?>
            <br>
            <?php echo date_to_text($WIKI_row['WIKI_date']); ?>
            <br><br><br>
            <form method="post">
                Er du sikker på at du vil slette?
                <input name="delete" type="submit" value="Slett">
            </form>
        </div>
    </div>

<?php } else {
    echo feedback("Velg wiki via wiki-siden", "blue");
} ?>