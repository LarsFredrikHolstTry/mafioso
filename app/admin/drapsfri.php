<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['register'])) {
    $start = strtotime($_POST['start_time']);
    $end = strtotime($_POST['end_time']);

    if ($start > $end) {
        echo feedback("Start-tiden kan ikke være mindre enn slutt-tiden", "fail");
    } else {
        $sql = "INSERT INTO drapsfri (DRAPFRI_start, DRAPFRI_end) VALUES (?,?)";
        $pdo->prepare($sql)->execute([$start, $end]);

        echo feedback("Drapsfri er registrert", "success");
    }
}

if (isset($_GET['abort'])) {
    $sql = "DELETE FROM drapsfri WHERE DRAPFRI_id = " . $_GET['abort'] . "";
    $pdo->exec($sql);

    echo feedback("Drapsfri ble avbrutt", "success");
}

?>

<div class="content">
    <h4 style="margin-bottom: 10px;">Drapsfri</h4>
    <form method="post">
        <div style="float: left; width: 50%;">
            Start-tid<br>
            <input type="datetime-local" id="birthdaytime" name="start_time" required>
        </div>
        <div style="float: left; width: 50%;">
            Slutt-tid<br>
            <input type="datetime-local" id="birthdaytime" name="end_time" required>
        </div>
        <input type="submit" value="Registrer neste drapsfri" name="register">
    </form>
    <br>
    <h3 style="margin-bottom: 10px;">Planlagte drapsfri</h3>
    <table>
        <tr>
            <th>Start</th>
            <th>Slutt</th>
            <th>Handling</th>
        </tr>
        <?php

        $sql = "SELECT * FROM drapsfri ORDER BY DRAPFRI_start";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr>
                <td><?php echo date_to_text($row['DRAPFRI_start']); ?></td>
                <td><?php echo date_to_text($row['DRAPFRI_end']); ?></td>
                <td><a href="?side=admin&adminside=drapsfri&abort=<?php echo $row['DRAPFRI_id']; ?>">Fjern</a></td>
            </tr>
        <?php } ?>
    </table>
</div>