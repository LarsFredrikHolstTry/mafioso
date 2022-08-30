<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['create'])) {
    $title = htmlspecialchars($_POST['title']);
    $text = $_POST['text'];

    $date = time();

    $sql = "INSERT INTO wiki (WIKI_title, WIKI_desc, WIKI_date) VALUES (?,?,?)";
    $pdo->prepare($sql)->execute([$title, $text, $date]);

    echo feedback("Ny wiki post er opprettet!", "success");
}

?>

<div class="content">
    <h4 style="margin-bottom: 10px;">Ny wiki post</h4>
    <form method="post">
        <h4 style="margin-bottom: 10px;">Tittel</h4>
        <input name="title" type="text">
        <h4 style="margin-bottom: 10px;">Tekst</h4>
        <textarea name="text" rows="20"></textarea>
        <input type="submit" name="create" value="Opprett">
    </form>
</div>