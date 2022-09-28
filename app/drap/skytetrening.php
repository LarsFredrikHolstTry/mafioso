<?php

if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {



    function weapon_price($weapon_nr)
    {
        $weapon_price[0] = 25000;
        $weapon_price[1] = $weapon_price[0] + 150;
        $weapon_price[2] = $weapon_price[1] + 150;
        $weapon_price[3] = $weapon_price[2] + 150;
        $weapon_price[4] = $weapon_price[3] + 150;
        $weapon_price[5] = $weapon_price[4] + 150;
        $weapon_price[6] = $weapon_price[5] + 150;
        $weapon_price[7] = $weapon_price[6] + 150;
        $weapon_price[8] = $weapon_price[7] + 150;
        $weapon_price[9] = $weapon_price[8] + 150;
        $weapon_price[10] = $weapon_price[9] + 150;
        $weapon_price[11] = $weapon_price[10] + 150;
        $weapon_price[12] = $weapon_price[11] + 150;
        $weapon_price[13] = $weapon_price[12] + 150;
        $weapon_price[14] = $weapon_price[13] + 150;
        $weapon_price[15] = $weapon_price[14] + 150;
        $weapon_price[16] = $weapon_price[15] + 150;
        $weapon_price[17] = $weapon_price[16] + 10000;

        return $weapon_price[$weapon_nr];
    }

    function train_weapon($id, $percentage, $pdo)
    {
        $sql = "UPDATE accounts_stat SET AS_weapon_progress = (AS_weapon_progress + $percentage) WHERE AS_id='" . $id . "'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    $weapon_price = weapon_price(AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo));
    if (AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo) >= 17) {
        $training_percentage = 1;
    } else {
        $training_percentage = 2;
    }

    $cooldown = 500 + time();

    if (isset($_POST['train'])) {
        if (!weapon_ready($_SESSION['ID'], $pdo)) {
            if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= $weapon_price) {
                weapon_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                train_weapon($_SESSION['ID'], $training_percentage, $pdo);
                take_money($_SESSION['ID'], $weapon_price, $pdo);

                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 6) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }

                if (AS_session_row($_SESSION['ID'], 'AS_weapon_progress', $pdo) >= 100) {
                    $median = AS_session_row($_SESSION['ID'], 'AS_weapon_progress', $pdo) - 100;
                    $sql = "UPDATE accounts_stat SET AS_weapon_progress = $median, AS_weapon = (AS_weapon + 1) WHERE AS_id='" . $_SESSION['ID'] . "'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();

                    $text = "Du har fullført skytetrening og har fått " . weapons(AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo)) . " som nytt våpen";
                    send_notification($_SESSION['ID'], $text, $pdo);
                }

                header("Location: ?side=drap&p=skytetrening&trent");
            } else {
                echo feedback("Du har ikke nok penger til å trene våpen", "fail");
            }
        } else {
            echo feedback("Du har ventetid.", "blue");
        }
    }

    if (isset($_GET['trent'])) {
        echo feedback("Du trente i skytetrening", "success");
    }

?>


    <div class="col-7 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/skytetrening.png">
            <p class="description">På skytetrening så spesialiserer du deg i våpen, du må ta skytetrening for å få bedre våpen. Du kan trene hvert 15 minutt.</p>
            <h4 style="text-align: center; margin-bottom: 10px;">Du har: <?php echo weapons(AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo)); ?></h4>
            <?php if (AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo) >= 19) {
                echo feedback("Du har det beste våpenet", "blue");
            } else { ?>

                <center style="margin: 10px 0px;">
                    <div class="rankbar_gun">
                        <p class="description">Framgang: <?php echo round(AS_session_row($_SESSION['ID'], 'AS_weapon_progress', $pdo), 2); ?>%</p>
                        <div id="progress_gun"></div>
                    </div>
                </center>

                <style>
                    #progress_gun:after {
                        width: <?php echo round(AS_session_row($_SESSION['ID'], 'AS_weapon_progress', $pdo), 2) . '%'; ?>;
                    }
                </style>

                <?php

                if (weapon_ready($_SESSION['ID'], $pdo)) {

                    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                    $stmt->execute(array(
                        ':cd_id' => $_SESSION['ID']
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $time_left = $row['CD_weapon'] - time();

                    $minutes = floor($time_left / 60);
                    $seconds = $time_left - $minutes * 60;

                    echo feedback('Du har nylig hatt skytetrening. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan skyte igjen.', 'cooldown'); ?>
                    <script>
                        timeleft(<?php echo $time_left; ?>, "countdowntimer");
                    </script>
                <?php } else { ?>

                    <form method="post">
                        <p class="description" style="text-align: center;">Pris for skytetrening er <?php echo number($weapon_price); ?>kr</p>
                        <input type="submit" style="width: 100%;" name="train" value="Tren skytetrening">
                    </form>

            <?php }
            } ?>
        </div>
    </div>
<?php } ?>