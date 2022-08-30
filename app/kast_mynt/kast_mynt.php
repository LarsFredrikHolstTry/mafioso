<?php
if (!isset($_GET['side'])) {
    echo "<center><h3>404 Not Found</h3></center><br><hr>";
} else {

    $type[0] = "Kron";
    $type[1] = "Mynt";

    $minbet = 100000;


    if (isset($_POST['play_game'])) {
        user_log($_SESSION['ID'], $_GET['side'], "Starter spill", $pdo);
        $innsats =  remove_space($_POST['innsats']);
        $type =     $_POST['kron_or_mynt'];

        $countdown = 43200 + time();

        $stmt = $pdo->prepare('SELECT * FROM kast_mynt WHERE KM_player_1 = ?');
        $stmt->bindParam(1, $_SESSION['ID'], PDO::PARAM_INT);
        $stmt->execute();
        $session_active = $stmt->fetch(PDO::FETCH_ASSOC);

        if (is_numeric($innsats)) {
            if ($session_active) {
                user_log($_SESSION['ID'], $_GET['side'], "Allerede aktivt spill", $pdo);
                echo feedback("Du har allerede ett aktivt spill", "error");
            } else {
                if ($innsats > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                    user_log($_SESSION['ID'], $_GET['side'], "Prøver å satse mer enn på hånden", $pdo);
                    echo feedback("Du kan ikke satse mer enn du har på hånden", "error");
                } elseif ($innsats < $minbet) {
                    user_log($_SESSION['ID'], $_GET['side'], "Prøver å satse mindre enn " . number($minbet) . "", $pdo);
                    echo feedback("Du kan ikke satse mindre enn " . number($minbet) . " kr", "error");
                } else {
                    $sql = "INSERT INTO kast_mynt (KM_player_1, KM_kron_mynt, KM_bet, KM_countdown) VALUES (?,?,?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['ID'], $type, $innsats, $countdown]);

                    take_money($_SESSION['ID'], $innsats, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Aktivt spill venter på spiller. Innsats:" . $innsats, $pdo);
                    header("Location: ?side=kast_mynt");
                }
            }
        } else {
            echo feedback("Ugyldig input", "error");
        }
    }

    if (isset($_GET['play'])) {
        user_log($_SESSION['ID'], $_GET['side'], "Spiller mot annen spiller", $pdo);

        $stmt = $pdo->prepare('SELECT * FROM kast_mynt WHERE KM_id = ?');
        $stmt->bindParam(1, $_GET['play'], PDO::PARAM_INT);
        $stmt->execute();
        $check_active = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($check_active) {

            $stmt = $pdo->prepare('SELECT * FROM kast_mynt WHERE KM_id = ?');
            $stmt->bindParam(1, $_GET['play'], PDO::PARAM_INT);
            $stmt->execute();
            $row_ks = $stmt->fetch(PDO::FETCH_ASSOC);

            $bet =          $row_ks['KM_bet'];
            $player_1 =     $row_ks['KM_player_1'];

            $stmt = $pdo->prepare('SELECT * FROM accounts_stat WHERE AS_id = ?');
            $stmt->bindParam(1, $player_1, PDO::PARAM_INT);
            $stmt->execute();
            $row_player_1 = $stmt->fetch(PDO::FETCH_ASSOC);

            $money_player_1 = $row_player_1['AS_money'];
            $bank_money_player_1 = $row_player_1['AS_bankmoney'];

            if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < $bet) {
                user_log($_SESSION['ID'], $_GET['side'], "Prøver å spille for mer enn på hånden", $pdo);
                echo feedback("Du kan ikke spille for mer enn du har på hånden.", "error");
            } elseif ($_SESSION['ID'] == $row_ks['KM_player_1']) {
                user_log($_SESSION['ID'], $_GET['side'], "Prøver å spille mot seg selv", $pdo);
                echo feedback("Du kan ikke spille mot deg selv.", "error");
            } else {
                $k_m = mt_rand(0, 1);

                if ($row_ks['KM_kron_mynt'] == $k_m) {
                    $amount = ((($bet * 2) * 0.9) - $bet);

                    give_money($_SESSION['ID'], $amount, $pdo);

                    if (get_cashback_saldo($player_1, $pdo) < get_max_cashback(active_vip($player_1, $pdo))) {
                        give_cashback($player_1, $amount, $pdo);
                    }

                    if (get_cashback_saldo($_SESSION['ID'], $pdo) < get_max_cashback(active_vip($_SESSION['ID'], $pdo))) {
                        give_cashback($_SESSION['ID'], $amount, $pdo);
                    }

                    $date = time();
                    $sql = "INSERT INTO notification (NO_acc_id, NO_text, NO_date) VALUES (?,?,?)";
                    $pdo->prepare($sql)->execute([$player_1, "Du ble tatt på kast mynt og tapte " . number($bet) . "kr mot " . username_plain($_SESSION['ID'], $pdo) . "", time()]);

                    $sql = "DELETE FROM kast_mynt WHERE KM_player_1 = '" . $player_1 . "'";
                    $pdo->exec($sql);

                    update_gambling($_SESSION['ID'], $amount, $pdo);
                    update_gambling($player_1, -$bet, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Vant " . $amount . " kr på kast mynt mot " . username_plain($player_1, $pdo) . "", $pdo);
                    header("Location: ?side=kast_mynt&win=" . $bet . "&km=" . $k_m . "");
                } else {
                    $amount = ((($bet * 2) * 0.9));

                    $date = time();
                    $sql = "INSERT INTO notification (NO_acc_id, NO_text, NO_date) VALUES (?,?,?)";
                    $pdo->prepare($sql)->execute([$player_1, "Du ble tatt på kast mynt og vant " . number(($bet * 2) * 0.9) . "kr mot " . username_plain($_SESSION['ID'], $pdo) . "", time()]);

                    if (get_cashback_saldo($player_1, $pdo) < get_max_cashback(active_vip($player_1, $pdo))) {
                        give_cashback($player_1, $amount, $pdo);
                    }

                    if (get_cashback_saldo($_SESSION['ID'], $pdo) < get_max_cashback(active_vip($_SESSION['ID'], $pdo))) {
                        give_cashback($_SESSION['ID'], $amount, $pdo);
                    }

                    take_money($_SESSION['ID'], $bet, $pdo);
                    give_money($player_1, $amount, $pdo);

                    $sql = "DELETE FROM kast_mynt WHERE KM_player_1 = '" . $player_1 . "'";
                    $pdo->exec($sql);

                    update_gambling($player_1, $amount, $pdo);
                    update_gambling($_SESSION['ID'], -$bet, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Tapte " . $amount . " kr på kast mynt mot " . username_plain($player_1, $pdo) . "", $pdo);
                    header("Location: ?side=kast_mynt&loose=" . $bet . "&km=" . $k_m . "");
                }
            }
        } else {
            user_log($_SESSION['ID'], $_GET['side'], "Spillet er utgått", $pdo);
            echo feedback('Du var for sen.', "error");
        }
    }

    $kron_eller_mynt[0] = "mynt";
    $kron_eller_mynt[1] = "kron";

    if (isset($_GET['loose'])) {
        echo feedback('Du fikk ' . $kron_eller_mynt[$_GET['km']] . ' og taper ' . number($_GET['loose']) . 'kr', "error");
    } elseif (isset($_GET['win'])) {
        echo feedback('Du fikk ' . $kron_eller_mynt[$_GET['km']] . ' og vinner ' . number(($_GET['win'] * 2) * 0.9) . 'kr', "success");
    }

    $rand = mt_rand(0, 1);


?>
    <!-- CONTENT -->

    <div class="col-12" style="margin-top: -20px;">
        <div class="col-7">
            <div class="content">
                <form action="" method="post">
                    <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                    <p class="description">Staten tar 10% av vinnersummen<br>
                        Minstebeløp: <?php echo number($minbet); ?> kr</p>
                    <div class="col-5">
                        <input type="text" name="innsats" id="number" placeholder="Innsats" required>
                    </div>
                    <div class="col-2" style="margin-top: 7px;">
                        <label class="label_container">Kron
                            <input type="radio" name="kron_or_mynt" value="0" <?php if ($rand == 0) {
                                                                                    echo 'checked';
                                                                                } ?>>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-2" style="margin-top: 7px;">
                        <label class="label_container">Mynt
                            <input type="radio" name="kron_or_mynt" value="1" <?php if ($rand == 1) {
                                                                                    echo 'checked';
                                                                                } ?>>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-3">
                        <input type="submit" name="play_game" style="width: 100%;" value="Spill">
                    </div>
                    <div style="clear: both;"></div>
                </form>
            </div>
        </div>
        <div class="col-5">
            <?php

            $stmt = $pdo->prepare('SELECT * FROM kast_mynt');
            $stmt->execute();
            $check_active = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($check_active) {

            ?>
                <div class="content">
                    <form action="" method="post">
                        <div class="header">
                            <span>Aktive spill</span>
                        </div>
                        <table>
                            <tr>
                                <th style="width: 40%;">Bruker</th>
                                <th style="width: 25%;">Valg</th>
                                <th style="width: 25%">Beløp</th>
                                <th style="width: 10%"></th>
                            </tr>
                            <?php

                            $sql = "SELECT * FROM kast_mynt WHERE NOT KM_player_1 = 0 ORDER BY KM_bet ASC";
                            $stmt = $pdo->query($sql);
                            while ($row_km = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                $player_1 =     $row_km['KM_player_1'];
                                $kron_mynt =    $row_km['KM_kron_mynt'];
                                $bet =          $row_km['KM_bet'];

                            ?>
                                <tr style="height: 30px;">
                                    <td><?php echo username_plain($player_1, $pdo); ?></td>
                                    <td><?php echo $type[$kron_mynt]; ?></td>
                                    <td><?php echo number($bet); ?></td>
                                    <td><a href="?side=kast_mynt&play=<?php echo $row_km['KM_id']; ?>">Spill</a></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
            <?php } else {
                echo feedback('Det er for tiden ingen aktive spill', "blue");
            } ?>
        </div>
    </div>
    <script>
        $('#number').on("keyup", function(e) {
            if (e.keyCode !== 39 && e.keyCode !== 37) {
                this.value = this.value.replace(/ /g, '');
                var number = this.value;
                this.value = number.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            }
        });
    </script>

<?php } ?>