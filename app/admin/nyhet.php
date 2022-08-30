<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['new'])) {
    $tittel = $_POST['tittel'];
    $tekst = $_POST['text'];

    $sql = "INSERT INTO nyheter (NYH_title, NYH_text, NYH_writer, NYH_date) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tittel, $tekst, $_SESSION['ID'], time()]);

    echo feedback("Nyhet ble publisert!", "success");
}

?>
<div class="content">
    <h3 style="margin-bottom: 10px;">Nyhet</h3>
    <form method="post">
        <p>Tittel</p>
        <input type="text" name="tittel">
        <p>Tekst</p>
        <?php include_once '././textarea_style.php'; ?>
        <textarea name="text" id="txtarea" style="margin-top: 0px; border-top: none; border-radius: 0px;" rows="20"></textarea>
        <input type="submit" name="new" value="Post nyhet">
    </form>
</div>