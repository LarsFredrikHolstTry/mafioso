<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['new'])) {
    $tekst = $_POST['text'];

    $stmt = $pdo->prepare('SELECT ACC_id FROM accounts');
    $stmt->execute(array());

    foreach ($stmt as $row) {
        send_notification($row['ACC_id'], $tekst, $pdo);
    }

    echo feedback("Varsel til alle ble sendt!", "success");
}

?>
<div class="content">
    <h3 style="margin-bottom: 10px;">Send varsel til alle</h3>
    <form method="post">
        <p>Tekst</p>
        <textarea name="text" id="txtarea" style="margin-top: 0px; border-radius: 0px;" rows="20"></textarea>
        <input type="submit" name="new" value="Send varsel">
    </form>
</div>