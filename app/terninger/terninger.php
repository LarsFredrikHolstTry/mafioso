<?php

$min_bet = 100;

if (active_vip($_SESSION['ID'], $pdo)) {
    $max_bet = 1000000000000000000000000000000000000;
} else {
    $max_bet = 5000000000000000000000000000000000000;
}

if (isset($_POST['play'])) {
    user_log($_SESSION['ID'], $side, "Lager nytt spill", $pdo);
    $value = remove_space($_POST['value']);

    if (active_terning($_SESSION['ID'], $pdo)) {
        user_log($_SESSION['ID'], $side, "Allerede i aktivt spill", $pdo);
        echo feedback("Du har allerede ette aktivt spill", "error");
    } elseif ($value >= $min_bet && is_numeric($value) && $value <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo) && $value <= $max_bet) {
        $timeout = time() + 43200;
        $total_dice = 0;
        $dice_array = array();

        for ($i = 0; $i < 6; $i++) {
            $dice = mt_rand(1, 6);
            array_push($dice_array, $dice);
            $total_dice = $total_dice + $dice;
        }

        $dice_array = json_encode($dice_array);

        $sql = "INSERT INTO terninger (TERN_player, TERN_amount, TERN_dices, TERN_bet, TERN_date) VALUES (?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['ID'], $total_dice, $dice_array, $value, $timeout]);

        take_money($_SESSION['ID'], $value, $pdo);

        user_log($_SESSION['ID'], $side, "Spill startet", $pdo);
        echo feedback("Spillet er startet", "success");
    } else {
        user_log($_SESSION['ID'], $side, "Ugyldig input: " . $value, $pdo);
        echo feedback("Ugyldig input", "error");
    }
}

if (isset($_GET['spill'])) {
    user_log($_SESSION['ID'], $side, "Starter spill", $pdo);
    $query = $pdo->prepare("SELECT TERN_player, TERN_amount, TERN_dices, TERN_bet FROM terninger WHERE TERN_id=?");
    $query->execute(array($_GET['spill']));
    $TERN_row = $query->fetch(PDO::FETCH_ASSOC);

    if ($TERN_row) {
        /* DATABASE DATA */
        $total_dice = $TERN_row['TERN_amount'];
        $dice_array = json_decode($TERN_row['TERN_dices']);
        $player = $TERN_row['TERN_player'];
        $bet = $TERN_row['TERN_bet'];
        /* DATABASE DATA */

        if ($player == $_SESSION['ID']) {
            $total_dice_bot = 0;
            $dice_array_bot = array();

            for ($i = 0; $i < 6; $i++) {
                $dice = mt_rand(1, 6);
                array_push($dice_array_bot, $dice);
                $total_dice_bot = $total_dice_bot + $dice;
            }

            if ($total_dice > $total_dice_bot) {
                give_money($_SESSION['ID'], $bet * 2, $pdo);

                update_gambling($_SESSION['ID'], $bet, $pdo);

                user_log($_SESSION['ID'], $side, "Vinner spillet og " . ($bet * 2) . "kr", $pdo);
                echo feedback("Du vant over boten!", "success");
            } elseif ($total_dice_bot > $total_dice) {
                update_gambling($_SESSION['ID'], -$bet, $pdo);

                user_log($_SESSION['ID'], $side, "Taper spillet og " . $bet . "kr", $pdo);
                echo feedback("Du tapte over boten!", "error");
            } else {
                give_money($_SESSION['ID'], $bet, $pdo);

                user_log($_SESSION['ID'], $side, "Uavgjort og får " . $bet . "kr tilbake", $pdo);
                echo feedback("Det ble uavgjort", "fail");
            }

            echo '<div class="col-12 single" style="padding-bottom: 5px;">
            <div class="content">
            <div class="pad_10"><h3>Boten sine terninger (' . $total_dice_bot . '):</h3>';
            for ($t = 0; $t < 6; $t++) {
                echo '<img style="float: left; width: 30px; padding: 2px; height: auto;" src="app/terninger/img/' . $dice_array_bot[$t] . '.png">';
            }
            echo '<br>';
            echo '<br><h3>Dine terninger (' . $total_dice . '):</h3>';
            for ($p = 0; $p < 6; $p++) {
                echo '<img style="float: left; width: 30px; padding: 2px; height: auto;" src="app/terninger/img/' . $dice_array[$p] . '.png">';
            }
            echo '<br><br></div>
                </div>
            </div>';

            $sql = "DELETE FROM terninger WHERE TERN_id = " . $_GET['spill'] . "";
            $pdo->exec($sql);
        } elseif ($bet > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
            user_log($_SESSION['ID'], $side, "Innsatsen for høy", $pdo);
            echo feedback("Innsatsen er høyere enn du har på hånden.", "error");
        } else {
            take_money($_SESSION['ID'], $bet, $pdo);

            /* SESSION DATA */
            $total_dice_session = 0;
            $dice_array_session = array();

            if (get_cashback_saldo($player, $pdo) < get_max_cashback(active_vip($player, $pdo))) {
                give_cashback($player, $bet, $pdo);
            }

            if (get_cashback_saldo($_SESSION['ID'], $pdo) < get_max_cashback(active_vip($_SESSION['ID'], $pdo))) {
                give_cashback($_SESSION['ID'], $bet, $pdo);
            }

            give_cashback($player, $bet, $pdo);
            give_cashback($_SESSION['ID'], $bet, $pdo);

            for ($i = 0; $i < 6; $i++) {
                $dice = mt_rand(1, 6);
                array_push($dice_array_session, $dice);
                $total_dice_session = $total_dice_session + $dice;
            }

            if ($total_dice_session > $total_dice) {
                give_money($_SESSION['ID'], $bet * 2, $pdo);
                send_notification($player, "Du tapte  " . number($bet) . " kr på terninger med " . $total_dice . " mot " . $total_dice_session, $pdo);

                update_gambling($_SESSION['ID'], $bet, $pdo);
                update_gambling($player, -$bet, $pdo);

                user_log($_SESSION['ID'], $side, "Vinner spillet og " . number($bet * 2) . "kr", $pdo);
                user_log($player, $side, "Taper spillet og " . number($bet) . "kr", $pdo);
                echo feedback("Du vant!", "success");
            } elseif ($total_dice > $total_dice_session) {
                give_money($player, $bet * 2, $pdo);
                send_notification($player, "Du vant  " . number($bet * 2) . " kr på terninger med " . $total_dice . " mot " . $total_dice_session, $pdo);


                update_gambling($_SESSION['ID'], -$bet, $pdo);
                update_gambling($player, $bet, $pdo);

                user_log($_SESSION['ID'], $side, "Taper spillet og " . number($bet) . "kr", $pdo);
                user_log($player, $side, "Vinner spillet og " . number($bet * 2) . "kr", $pdo);
                echo feedback("Du tapte!", "error");
            } else {
                give_money($player, $bet, $pdo);
                give_money($_SESSION['ID'], $bet, $pdo);

                send_notification($player, "Du ble tatt på terninger, dere hadde begge " . $total_dice . " i score og det ble uavgjort.", $pdo);
                user_log($_SESSION['ID'], $side, "Uavgjort og får " . $bet . "kr tilbake", $pdo);
                echo feedback("Det ble uavgjort", "fail");
            }

            echo '<div class="col-12 single" style="padding-bottom: 5px;">
        <div class="content">
            <div class="pad_10"><h3>Dine terninger (' . $total_dice_session . '):</h3>';
            for ($t = 0; $t < 6; $t++) {
                echo '<img style="float: left; width: 30px; padding: 2px; height: auto;" src="app/terninger/img/' . $dice_array_session[$t] . '.png">';
            }
            echo '<br>';
            echo '<br><h3>Motstanderens terninger (' . $total_dice . '):</h3>';
            for ($p = 0; $p < 6; $p++) {
                echo '<img style="float: left; width: 30px; padding: 2px; height: auto;" src="app/terninger/img/' . $dice_array[$p] . '.png">';
            }
            echo '<br><br></div>
                </div>
            </div>';

            $sql = "DELETE FROM terninger WHERE TERN_id = " . $_GET['spill'] . "";
            $pdo->exec($sql);
        }
    } else {
        echo feedback("Ugyldig spill", "error");
    }
}

?>
<div class="col-12 single" style="margin-top: -10px;">
    <div class="col-7">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <p class="description">På terninger får du kaste 6 terninger, den med høyeste verdi på alle terningene vinner. Du får ikke vite ditt beløp før du blir tatt.</p>
            <h4 style="margin-bottom: 10px;">Innsats</h4>
            <form method="post">
                <input type="text" style="width: 70%;" placeholder="Beløp" name="value" id="number">
                <input type="submit" style="width: 28%;" name="play" value="Spill">
            </form>
        </div>
    </div>
    <div class="col-5">
        <div class="content">
            <h4 style="margin-bottom: 10px;">Aktive spill</h4>
            <?php

            $query = $pdo->prepare("SELECT * FROM terninger");
            $query->execute(array());
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                echo feedback("Det finnes ingen spill", "error");
            } else {

            ?>
                <table>
                    <tr>
                        <th style="text-align: left;">Spiller</th>
                        <th style="text-align: center;">Beløp</th>
                        <th style="text-align: right;">Handling</th>
                    </tr>
                    <?php


                    $query = $pdo->prepare('SELECT * FROM terninger');
                    $query->execute();
                    foreach ($query as $row) {

                    ?>
                        <tr>
                            <td style="text-align: left;"><a href="?side=profil&id=<?php echo $row['TERN_player']; ?>"><?php echo ACC_username($row['TERN_player'], $pdo); ?></a></td>
                            <td style="text-align: center;"><?php echo number($row['TERN_bet']); ?> kr</td>
                            <?php if ($row['TERN_player'] != $_SESSION['ID']) { ?>
                                <td style="text-align: right;"><a href="?side=terninger&spill=<?php echo $row['TERN_id']; ?>">Spill</a></td>
                            <?php } else { ?>
                                <td style="text-align: right;"><a href="?side=terninger&spill=<?php echo $row['TERN_id']; ?>">Spill mot bot</a></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>
</div>
<style>
    @media only screen and (max-width: 1200px) {
        .fifty {
            width: 100% !important;
        }
    }

    .float_left {
        float: left;
    }

    .fifty {
        width: 50%
    }

    .float_right {
        float: right;
    }
</style>

<script>
    number_space("#number");
</script>