<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['ban'])) {
    $user = $_POST['user'];
    $reason = $_POST['begrunnelse'];

    $sql = "INSERT INTO banned (BAN_acc_id, BAN_reason) VALUES (?,?)";
    $pdo->prepare($sql)->execute([$user, $reason]);

    $sql = "UPDATE accounts SET ACC_type = 5 WHERE ACC_id='" . $user . "'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    echo feedback("Brukeren ble bannet!", "success");
}

?>
<div class="content">
    <div class="col-6">
        <h3 style="margin-bottom: 10px;">Nyhet</h3>
        <form method="post">
            <p>Hvem ønsker du å utestenge? (ID)</p>
            <input type="text" name="user">
            <p>Begrunnelse</p>
            <input type="text" name="begrunnelse">
            <br><br>
            <input type="submit" name="ban" value="Ban bruker">
        </form>
    </div>
    <div class="col-6">
        <h4>Utestengte brukere</h4>
        <table>
            <tr>
                <th style="width: 35%;">Bruker</th>
                <th>Begrunnelse</th>
            </tr>
            <?php

            $sql = "SELECT * FROM banned";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            ?>
                <tr>
                    <td><?php echo ACC_username($row['BAN_acc_id'], $pdo); ?></td>
                    <td><?php echo $row['BAN_reason']; ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>
    <div style="clear: both;"></div>
</div>