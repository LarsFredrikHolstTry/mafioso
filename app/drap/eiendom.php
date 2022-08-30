<?php

$eiendom_pris[0] =  0;
$eiendom_pris[1] =  10000;
$eiendom_pris[2] =  50000;
$eiendom_pris[3] =  100000;
$eiendom_pris[4] =  1000000;
$eiendom_pris[5] =  5000000;
$eiendom_pris[6] =  10000000;
$eiendom_pris[7] =  25000000;
$eiendom_pris[8] =  100000000;
$eiendom_pris[9] =  250000000;
$eiendom_pris[10] = 2000000000;
$eiendom_pris[11] = 5000000000;
$eiendom_pris[12] = 10000000000;
$eiendom_pris[13] = 25000000000;

$eiendom[0] = "på gaten";
$eiendom[1] = "Telt";
$eiendom[2] = "Studenthybel";
$eiendom[3] = "Hybel";
$eiendom[4] = "Leilighet";
$eiendom[5] = "Rekkehus";
$eiendom[6] = "Tomannsbolig";
$eiendom[7] = "Enebolig";
$eiendom[8] = "Bygård";
$eiendom[9] = "Villa";
$eiendom[10] = "Herskapelig villa";
$eiendom[11] = "Herskapelig villa i Oslo";
$eiendom[12] = "Herskapelig villa i Las Vegas";
$eiendom[13] = "Herskapelig villa i New York";

$thing_add[0] = 0;
$thing_add[1] = 2;
$thing_add[2] = 4;
$thing_add[3] = 6;
$thing_add[4] = 8;
$thing_add[5] = 10;
$thing_add[6] = 15;
$thing_add[7] = 20;
$thing_add[8] = 30;
$thing_add[9] = 40;
$thing_add[10] = 50;
$thing_add[11] = 75;
$thing_add[12] = 100;
$thing_add[13] = 150;

$garage_add[0] = 0;
$garage_add[1] = 0;
$garage_add[2] = 2;
$garage_add[3] = 4;
$garage_add[4] = 6;
$garage_add[5] = 8;
$garage_add[6] = 10;
$garage_add[7] = 15;
$garage_add[8] = 30;
$garage_add[9] = 40;
$garage_add[10] = 50;
$garage_add[11] = 75;
$garage_add[12] = 100;
$garage_add[13] = 150;

$eiendom_type = AS_session_row($_SESSION['ID'], 'AS_eiendom', $pdo);

$addon_type;

if ($eiendom_type == 0) {
    $addon_type = " ";
} elseif ($eiendom_type != 1) {
    $addon_type = " i en ";
} elseif ($eiendom_type == 1) {
    $addon_type = " i et ";
} else {
    $addon_type = " ";
}

if (isset($_GET['upgrade'])) {
    echo feedback("Du har oppgradert eiendommen din!", "success");
}

if (isset($_POST['upgrade'])) {
    if ($eiendom_pris[$eiendom_type + 1] > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
        echo feedback("Du har ikke nok penger til å oppgradere eiendommen din", "error");
    } elseif ($eiendom_type == 13) {
        echo feedback("Du har allerede den beste eiendommen!", "error");
    } else {
        $sql = "UPDATE accounts_stat SET AS_eiendom = AS_eiendom + ? WHERE AS_id = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([1, $_SESSION['ID']]);

        upgrade_garage($_SESSION['ID'], $garage_add[$eiendom_type + 1], $pdo);
        upgrade_thing($_SESSION['ID'], $thing_add[$eiendom_type + 1], $pdo);
        take_money($_SESSION['ID'], $eiendom_pris[$eiendom_type + 1], $pdo);

        header("Location: ?side=drap&p=eiendom&upgrade");
    }
}

$total_garage = 0;
$total_things = 0;

for ($i = 0; $i < count($garage_add); $i++) {
    $total_garage = $total_garage + $garage_add[$i];
    $total_things = $total_things + $thing_add[$i];
}


if ($eiendom_type == 13) { ?>
    <div class="col-9 single">
        <div class="content">
            <img class="action_image" src="img/eiendom/<?php echo $eiendom_type; ?>.png">
            <h4 style="text-align: center;"><?php echo 'Du bor for tiden ' . $addon_type . ' ' . $eiendom[$eiendom_type]; ?></h4>
            <p>Du har den beste eiendommen og har fått totalt <b><?php echo number($total_garage); ?></b> ekstra plasser i <a href="?side=garasje">garasjen</a> og <b><?php echo number($total_things); ?></b> ekstra plasser til ting på <a href="?side=lager">lageret</a></p>
        </div>
    </div>
<?php } else { ?>
    <div class="col-9 single">
        <div class="content">
            <div class="col-6">
                <img class="action_image" src="img/eiendom/<?php echo $eiendom_type; ?>.png">
                <h4 style="text-align: center;"><?php echo 'Du bor for tiden ' . $addon_type . ' ' . $eiendom[$eiendom_type]; ?></h4>
                <p>Eiendommen din gir deg <b><?php echo number($garage_add[$eiendom_type]); ?></b> ekstra plasser i <a href="?side=garasje">garasjen</a> og <b><?php echo number($thing_add[$eiendom_type]); ?></b> ekstra plasser til ting på <a href="?side=lager">lageret</a></p>
            </div>

            <div class="col-6">
                <img class="action_image" src="img/eiendom/<?php echo $eiendom_type + 1; ?>.png">
                <h4 style="text-align: center;"><?php echo 'Neste oppgradering er: ' . $eiendom[$eiendom_type + 1]; ?></h4>
                <p>Eiendommen vil gi deg <b><?php echo number($garage_add[$eiendom_type + 1]); ?></b> ekstra plasser i <a href="?side=garasje">garasjen</a> og <b><?php echo number($thing_add[$eiendom_type + 1]); ?></b> ekstra plasser til ting på <a href="?side=lager">lageret</a></p>
                <form method="post">
                    <input type="submit" name="upgrade" value="Oppgrader for <?php echo number($eiendom_pris[$eiendom_type + 1]); ?> kr">
                </form>
            </div>
            <div style="clear: both;"></div>

        </div>
    </div>
<?php } ?>