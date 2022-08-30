<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['new'])) {
    $user = $_POST['tittel'];
    $tekst = $_POST['text'];

    $user = get_acc_id($user, $pdo);
    send_notification($user, $tekst, $pdo);

    echo feedback("Varsel ble sendt!", "success");
}

?>
<div class="content">
    <h3 style="margin-bottom: 10px;">Send varsel</h3>
    <form method="post">
        <p>Til</p>
        <input type="text" name="tittel">
        <p>Tekst</p>
        <textarea name="text" id="txtarea" style="margin-top: 0px; border-radius: 0px;" rows="20"></textarea>
        <input type="submit" name="new" value="Send varsel">
    </form>
</div>