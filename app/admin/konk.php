<?php

if (!isset($_GET['side'])) {
    die();
}

$default_prize[0] = 50000000;
$default_prize[1] = 40000000;
$default_prize[2] = 30000000;
$default_prize[3] = 20000000;

$titles[0] = "flest vellykkede kriminalitet";
$titles[1] = "flest vellykkede biltyveri";
$titles[2] = "flest vellykkede brekk";
$titles[3] = "flest vellykkede utbrytninger";

$dropdown_site[0] = 'kriminalitet';
$dropdown_site[1] = 'biltyveri';
$dropdown_site[2] = 'brekk';
$dropdown_site[3] = 'fengsel';

if (isset($_POST['register'])) {
    $chosen = $_POST['type'];
    $start = strtotime($_POST['start_time']);
    $end = strtotime($_POST['end_time']);
    $site = $dropdown_site[$chosen];

    $desc = 'Konkurransen går ut på å få ' . $titles[$chosen] . '. <br>
    <br>
    1 Krim = 1 poeng i konkurransen!
    <br>
    Det er også mulig å finne tilfeldige poeng når det er aktive konkurranser som kan brukes til forskjellige goder på <a 
     href="?side=poeng">poeng-siden</a>!';

    $prize = array();

    for ($i = 1; $i < 5; $i++) {
        array_push($prize, remove_space($_POST[$i . '_place']));
    }

    $prize_json = json_encode($prize);

    if ($start > $end) {
        echo feedback("Start-tiden kan ikke være mindre enn slutt-tiden", "fail");
    } else {
        $sql = "INSERT INTO konk (KONK_site, KONK_from, KONK_to, KONK_title, KONK_description, KONK_prize) VALUES (?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$site, $start, $end, $titles[$chosen], $desc, $prize_json]);

        echo feedback("Konkurransen er registrert", "success");
    }
}

if (isset($_GET['abort'])) {
    $sql = "DELETE FROM konk WHERE KONK_id = " . $_GET['abort'] . "";
    $pdo->exec($sql);

    echo feedback("Konkurransen ble fjernet", "success");
}

?>
<div class="content">
    <h4 style="margin-bottom: 10px;">Konkurranse</h4>
    <form method="post">
        <div style="float: left; width: 50%;">
            Start-tid<br>
            <input type="datetime-local" id="birthdaytime" name="start_time" required>
        </div>
        <div style="float: left; width: 50%;">
            Slutt-tid<br>
            <input type="datetime-local" id="birthdaytime" name="end_time" required>
        </div>
        <div style="clear: both;"></div>
        <p style="margin-top: 20px; margin-bottom: 10px;">Tittel</p>
        <select name="type">
            <?php for ($i = 0; $i < count($titles); $i++) { ?>
                <option value="<?php echo $i; ?>"><?php echo $titles[$i]; ?></option>
            <?php } ?>
        </select>
        <br>
        <div class="col-5">
            <p style="margin-top: 20px; margin-bottom: 20px;">Plasseringer</p>
            <?php for ($i = 1; $i < 5; $i++) { ?>
                <span><?php echo $i; ?>. plass</span>
                <input style="margin-bottom: 20px;" id="number_<?php echo $i; ?>" value="<?php echo number($default_prize[$i - 1]); ?>" type="text" name="<?php echo $i; ?>_place">
                kr
                <br>
            <?php } ?>
        </div>
        <div style="clear: both;"></div>
        <input type="submit" value="Registrer neste konkurranse" name="register">

    </form>
    <br>
    <h3 style="margin-bottom: 10px;">Planlagte konkurranser</h3>
    <table>
        <tr>
            <th>Start</th>
            <th>Slutt</th>
            <th>Tittel</th>
            <th>Handling</th>
        </tr>
        <?php

        //Henter alle konkuranser som ikke er avslutta
        $sql = "SELECT * FROM konk WHERE konk_to > UNIX_TIMESTAMP() ORDER BY KONK_from";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr>
                <td><?php echo date_to_text($row['KONK_from']); ?></td>
                <td><?php echo date_to_text($row['KONK_to']); ?></td>
                <td><?php echo $row['KONK_title']; ?></td>

                <td><a href="?side=admin&adminside=konk&abort=<?php echo $row['KONK_id']; ?>">Fjern</a></td>
            </tr>
        <?php } ?>
    </table>
</div>

<script>
    number_space("#number_0");
    number_space("#number_1");
    number_space("#number_2");
    number_space("#number_3");
    number_space("#number_4");
</script>