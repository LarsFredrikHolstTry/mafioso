<?php

if (!isset($_GET['side'])) {
    die();
}

if (isset($_POST['register'])) {
    $start = strtotime($_POST['start_time']);
    $end = strtotime($_POST['end_time']);
    $title = $_POST['title'];

    if ($start > $end) {
        echo feedback("Start-tiden kan ikke være mindre enn slutt-tiden", "fail");
    } else {
        $sql = "INSERT INTO super_helg (SHELG_start, SHELG_end, SHELG_title) VALUES (?,?,?)";
        $pdo->prepare($sql)->execute([$start, $end, $title]);

        echo feedback("Superhelg er registrert", "success");
    }
}

if (isset($_GET['abort'])) {
    $sql = "DELETE FROM super_helg WHERE SHELG_id = " . $_GET['abort'] . "";
    $pdo->exec($sql);

    echo feedback("Superhelg ble avbrutt", "success");
}

?>

<div class="content">
    <h4 style="margin-bottom: 10px;">Superhelg</h4>
    <form method="post">
        <div style="float: left; width: 33%;">
            Start-tid<br>
            <input type="datetime-local" id="birthdaytime" name="start_time" required>
        </div>
        <div style="float: left; width: 33%;">
            Slutt-tid<br>
            <input type="datetime-local" id="birthdaytime" name="end_time" required>
        </div>
        <div style="float: left; width: 34%;">
            Tittel<br>
            <input type="text" name="title" required>
        </div>
        <input type="submit" value="Registrer neste superhelg" name="register">
    </form>
    <br>
    <h3 style="margin-bottom: 10px;">Planlagte superhelger</h3>
    <table>
        <tr>
            <th>Start</th>
            <th>Slutt</th>
            <th>Tittel</th>
            <th>Handling</th>
        </tr>
        <?php

        $sql = "SELECT * FROM super_helg ORDER BY SHELG_start";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr>
                <td><?php echo date_to_text($row['SHELG_start']); ?></td>
                <td><?php echo date_to_text($row['SHELG_end']); ?></td>
                <td><?php echo $row['SHELG_title']; ?></td>
                <td><a href="?side=admin&adminside=2x_helg&abort=<?php echo $row['SHELG_id']; ?>">Fjern</a></td>
            </tr>
        <?php } ?>
    </table>
</div>