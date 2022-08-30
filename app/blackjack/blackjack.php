<?php

include_once 'functions/blackjack.php';

/** Remove 'false &&' to close blackack for everyone except Skitzo */
if (false && $_SESSION['ID'] != 1) {
    echo feedback('Blackjack er stengt', "error");
} else {

    $min_bet = 1000;

    if (get_maxbet(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
        $max_bet = get_maxbet(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
    } else {
        $max_bet = 500000000;
    }

?>

    <div class="col-8 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <p class="description" style="text-align: center;">Minste innsats: <?php echo number($min_bet); ?> kr | Maks innsats: <?php echo number($max_bet); ?> kr</p>
            <?php

            $_SESSION['user_hand'] = $_SESSION['user_hand'] ?? [];
            $_SESSION['dealer_hand'] = $_SESSION['dealer_hand'] ?? [];
            $_SESSION['used_cards'] = $_SESSION['used_cards'] ?? [];

            if (isset($_POST['newgame'])) {
                delete_active_bj($_SESSION['ID'], $pdo);
                end_game();
            }

            if (isset($_POST['ferdig'])) {
                delete_active_bj($_SESSION['ID'], $pdo);
                end_game();
                header("Location: ?side=blackjack");
            }

            $bj = false;
            $bust = false;
            $bj_active = get_bj_status($_SESSION['ID'], $pdo);
            $dealer_total = 0;
            $user_total = 0;
            $card_no = mt_rand(0, 51);

            if (!isset($_GET['fb'])) {
                $stand = false;
            }

            foreach ($_SESSION['dealer_hand'] as $val) {
                $dealer_total = $dealer_total + $card_value[$val];
            }

            foreach ($_SESSION['user_hand'] as $val) {
                $user_total = $user_total + $card_value[$val];
            }

            if ($dealer_total > 21) {
                $dealer_total = 0;

                foreach ($_SESSION['dealer_hand'] as $val) {
                    $dealer_total = $dealer_total + $card_value_with_ace[$val];
                }
            }

            if ($user_total > 21) {
                $user_total = 0;

                foreach ($_SESSION['user_hand'] as $val) {
                    $user_total = $user_total + $card_value_with_ace[$val];
                }
            }

            if (isset($_POST['start_game'])) {
                if (blackjack_exist($_SESSION['ID'], $pdo)) {
                    echo feedback("Du har allerede et aktivt spill", "error");
                } else {
                    if (blackjack_owner_incity(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {
                        echo feedback("Du kan ikke spille på din egen blackjack", "error");
                    } else {
                        if (isset($_POST['bet']) && !$bj_active) {
                            if (is_numeric(remove_space($_POST['bet'])) && remove_space($_POST['bet']) > 0 && remove_space($_POST['bet']) <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo) && remove_space($_POST['bet']) <= $max_bet && remove_space($_POST['bet']) >= $min_bet) {
                                $bet = remove_space($_POST['bet']);

                                start_blackjack($_SESSION['ID'], $bet, $pdo);
                                take_money($_SESSION['ID'], $bet, $pdo);
                                update_bedrift_inntekt($_SESSION['ID'], 4, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $bet, $pdo);

                                $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank + ? WHERE BJO_city = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$bet, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                for ($i = 0; $i < 2; $i++) {
                                    $card_no = mt_rand(0, 51);
                                    do {
                                        array_push($_SESSION['user_hand'], $card_no);
                                        array_push($_SESSION['used_cards'], $card_no);
                                    } while (!in_array($card_no, $_SESSION['used_cards']));
                                }

                                $card_no = mt_rand(0, 51);
                                do {
                                    array_push($_SESSION['dealer_hand'], $card_no);
                                    array_push($_SESSION['used_cards'], $card_no);
                                } while (!in_array($card_no, $_SESSION['used_cards']) && $dealer_total < 17);

                                foreach ($_SESSION['user_hand'] as $val) {
                                    $user_total = $user_total + $card_value[$val];
                                }

                                if ($user_total > 21) {
                                    $user_total = 0;

                                    foreach ($_SESSION['user_hand'] as $val) {
                                        $user_total = $user_total + $card_value_with_ace[$val];
                                    }
                                }

                                if ($user_total == 21) {
                                    $bj = true;
                                    end_blackjack($_SESSION['ID'], $pdo);

                                    $bet = get_bj_bet($_SESSION['ID'], $pdo);
                                    $win = $bet * 2.5;
                                    give_money($_SESSION['ID'], $win, $pdo);
                                    update_bedrift_inntekt($_SESSION['ID'], 4, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), -$win, $pdo);

                                    update_gambling($_SESSION['ID'], ($win - $bet), $pdo);

                                    if (money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > $win) {
                                        $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank - ? WHERE BJO_city = ?";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([$win, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                                    } else {
                                        $sql = "DELETE FROM blackjack_owner WHERE BJO_city = " . AS_session_row($_SESSION['ID'], 'AS_city', $pdo) . "";
                                        $pdo->exec($sql);
                                    }

                                    header("Location: ?side=blackjack&fb=bj");
                                } else {
                                    header("Location: ?side=blackjack");
                                }
                            } else {
                                echo feedback("Ugyldig innsats", "error");
                            }
                        }
                    }
                }
            }

            if (isset($_POST['push']) && !isset($_POST['stand'])) {
                do {
                    array_push($_SESSION['user_hand'], $card_no);
                    array_push($_SESSION['used_cards'], $card_no);
                } while (!in_array($card_no, $_SESSION['user_hand']) && !in_array($card_no, $_SESSION['used_cards']) && !in_array($card_no, $_SESSION['dealer_hand']));

                foreach ($_SESSION['user_hand'] as $val) {
                    $user_total = $user_total + $card_value[$val];
                }

                if ($user_total > 21) {
                    $user_total = 0;

                    foreach ($_SESSION['user_hand'] as $val) {
                        $user_total = $user_total + $card_value_with_ace[$val];
                    }
                }

                if ($user_total > 21) {
                    end_blackjack($_SESSION['ID'], $pdo);
                    $bet = get_bj_bet($_SESSION['ID'], $pdo);

                    update_gambling($_SESSION['ID'], -$bet, $pdo);

                    header("Location: ?side=blackjack&fb=ovr");
                } else {
                    header("Location: ?side=blackjack");
                }
            }

            if (isset($_POST['stand']) && !isset($_POST['push'])) {
                if (get_bj_status($_SESSION['ID'], $pdo) == 0) {

                    end_blackjack($_SESSION['ID'], $pdo);

                    $stand = true;
                    $dealer_stop = $dealer_total;
                    while ($dealer_stop < 17) {
                        $card_no = mt_rand(0, 51);
                        if (!in_array($card_no, $_SESSION['user_hand']) && !in_array($card_no, $_SESSION['used_cards']) && !in_array($card_no, $_SESSION['dealer_hand'])) {
                            array_push($_SESSION['dealer_hand'], $card_no);
                            array_push($_SESSION['used_cards'], $card_no);
                            $dealer_stop = $dealer_stop + $card_value_with_ace[$card_no];
                        }
                    }

                    if (get_cashback_saldo($_SESSION['ID'], $pdo) < get_max_cashback(active_vip($_SESSION['ID'], $pdo))) {
                        give_cashback($_SESSION['ID'], get_bj_bet($_SESSION['ID'], $pdo), $pdo);
                    }

                    if ($dealer_stop > 21 && $user_total <= 21) {
                        // Spiller vinner
                        end_blackjack($_SESSION['ID'], $pdo);

                        $win = get_bj_bet($_SESSION['ID'], $pdo);
                        update_gambling($_SESSION['ID'], $win, $pdo);

                        $win = $win * 2;
                        give_money($_SESSION['ID'], $win, $pdo);
                        update_bedrift_inntekt($_SESSION['ID'], 4, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), -$win, $pdo);

                        if (money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > $win && money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > 5000000) {
                            $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank - ? WHERE BJO_city = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$win, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                        } else {
                            $sql = "DELETE FROM blackjack_owner WHERE BJO_city = " . AS_session_row($_SESSION['ID'], 'AS_city', $pdo) . "";
                            $pdo->exec($sql);
                        }

                        header("Location: ?side=blackjack&fb=sv");
                    } elseif ($dealer_stop > $user_total && $dealer_stop <= 21) {
                        // Dealer vinner
                        end_blackjack($_SESSION['ID'], $pdo);

                        header("Location: ?side=blackjack&fb=dv");
                    } elseif ($user_total > $dealer_stop && $user_total <= 21) {
                        // Spiller vinner
                        end_blackjack($_SESSION['ID'], $pdo);

                        $win = get_bj_bet($_SESSION['ID'], $pdo);
                        update_gambling($_SESSION['ID'], $win, $pdo);

                        $win = $win * 2;
                        give_money($_SESSION['ID'], $win, $pdo);
                        update_bedrift_inntekt($_SESSION['ID'], 4, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), -$win, $pdo);

                        if (money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > $win && money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > 5000000) {
                            $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank - ? WHERE BJO_city = ?";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute([$win, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                        } else {
                            $sql = "DELETE FROM blackjack_owner WHERE BJO_city = " . AS_session_row($_SESSION['ID'], 'AS_city', $pdo) . "";
                            $pdo->exec($sql);
                        }

                        header("Location: ?side=blackjack&fb=sv");
                    } elseif ($user_total == $dealer_stop) {
                        // Push, penger tilbake

                        $win = get_bj_bet($_SESSION['ID'], $pdo);
                        give_money($_SESSION['ID'], $win, $pdo);
                        update_bedrift_inntekt($_SESSION['ID'], 4, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), -$win, $pdo);

                        update_gambling($_SESSION['ID'], $win, $pdo);

                        $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank - ? WHERE BJO_city = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$win, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                        header("Location: ?side=blackjack&fb=psh");
                    }
                } else {
                    echo feedback("Ugyldig handling", "error");
                }
            }

            if (count($_SESSION['dealer_hand']) > 1) {
                $stand = true;
            }

            if (isset($_GET['fb']) && $_GET['fb'] == "ovr") {
                end_blackjack($_SESSION['ID'], $pdo);
                $bet = get_bj_bet($_SESSION['ID'], $pdo);

                echo feedback("Du gikk over 21 og taper " . number($bet) . "kr", "error");

                delete_active_bj($_SESSION['ID'], $pdo);
            }

            if (isset($_GET['fb']) && $_GET['fb'] == "sv") {
                end_blackjack($_SESSION['ID'], $pdo);
                $bet = get_bj_bet($_SESSION['ID'], $pdo);

                echo feedback("Du vant " . number($bet * 2) . "kr", "success");

                delete_active_bj($_SESSION['ID'], $pdo);
            }

            if (isset($_GET['fb']) && $_GET['fb'] == "dv") {
                end_blackjack($_SESSION['ID'], $pdo);
                $bet = get_bj_bet($_SESSION['ID'], $pdo);

                echo feedback("Du tapte " . number($bet) . "kr", "error");

                delete_active_bj($_SESSION['ID'], $pdo);
            }

            if (isset($_GET['fb']) && $_GET['fb'] == "psh") {
                end_blackjack($_SESSION['ID'], $pdo);
                $bet = get_bj_bet($_SESSION['ID'], $pdo);

                echo feedback("Uavgjort. Du får tilbake " . number($bet) . "kr", "fail");

                delete_active_bj($_SESSION['ID'], $pdo);
            }

            if (isset($_GET['fb']) && $_GET['fb'] == "bj") {
                end_blackjack($_SESSION['ID'], $pdo);
                $bet = get_bj_bet($_SESSION['ID'], $pdo);

                echo feedback("Blackjack! Du vant " . number($bet * 2.5) . "kr", "success");

                delete_active_bj($_SESSION['ID'], $pdo);
            }

            if (empty($_SESSION['user_hand'])) {

            ?>
                <form method="post" name="test">
                    <center>
                        <input id="number" style="width: 50%;" type="text" name="bet" placeholder="Beløp">
                        <input type="submit" name="start_game" value="Spill">
                        <br><br>
                        <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number($min_bet); ?>'" value="Min bet" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number($max_bet); ?>'" value="Max bet" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.1); ?>'" value="10%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.25); ?>'" value="25%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.5); ?>'" value="50%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo) * 0.75); ?>'" value="75%" /> <input type="button" onclick="document.forms['test']['bet'].value = '<?php echo number(AS_session_row($_SESSION['ID'], 'AS_money', $pdo)); ?>'" value="All in" />
                    </center>
                </form>
            <?php } else { ?>
                <div id="cards_container">
                    <?php

                    foreach ($_SESSION['dealer_hand'] as $value) {
                        echo "<img style='width: 85px; height: auto;' src='app/blackjack/cards/" . $cards_v2[$value] . ".svg'>";
                    }

                    if (count($_SESSION['dealer_hand']) == 1) {
                        echo "<img style='width: 85px; height: auto;' src='app/blackjack/cards/1B.svg'>";
                    }

                    echo '<br><br><span style="background-color: #2c2c2c; padding: 5px 7px; border-radius: 3px;">Dealer: <span style="color: white;">' . $dealer_total . '</span></span><br><br>';

                    foreach ($_SESSION['user_hand'] as $value) {
                        echo "<img style='width: 85px; height: auto;' src='app/blackjack/cards/" . $cards_v2[$value] . ".svg'>";
                    }

                    echo '<br><br><span style="background-color: #2c2c2c; padding: 5px 7px; border-radius: 3px;">Spiller: <span style="color: white;">' . $user_total . '</span></span><br><br>';

                    if (!blackjack_exist($_SESSION['ID'], $pdo) || $bj_active == 1) { ?>

                        <form method="post"><input type="submit" name="ferdig" value="Nytt spill"></form>
                    <?php } elseif ($bj_active == 0) { ?>
                        <form method="post"><input style="width: 100px;" type="submit" name="push" value="Nytt kort"> <input style="width: 100px;" type="submit" name="stand" value="Stå"></form>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (empty($_SESSION['user_hand']) || !blackjack_exist($_SESSION['ID'], $pdo)) { ?>

                <h3>Regler</h3>
                <p class="description">Målet med spillet er å få 21, eller nærmere denne poengsummen enn dealeren. Både spiller og dealer vil få to kort hver. Spilleren vil derimot kun se det ene kortet til dealeren, mens det andre kortet vil ligge vendt ned mot bordet. Heretter vil spilleren måtte velge om han skal ha flere kort, eller «stå» med kortene han allerede har. Dealeren må alltid stå på 17, noe som betyr at hvis han havner på 17 eller over, vil han ikke kunne trekke flere kort. (<a href="https://no.wikipedia.org/wiki/Blackjack" target="_blank">Wikipedia</a>)</p>

                <h3>Eierskap</h3>
                <?php

                $price = 100000000;

                if (isset($_POST['buy_blackjack'])) {
                    if (blackjack_owner_amount($_SESSION['ID'], $pdo)) {
                        echo feedback("Du eier allerede en blackjack", "error");
                    } else {
                        if (!blackjack_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                            if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= $price) {
                                take_money($_SESSION['ID'], $price, $pdo);

                                $new_maxbet = 50000000;

                                $sql = "INSERT INTO blackjack_owner (BJO_city, BJO_owner, BJO_bank, BJO_maksbet) VALUES (?,?,?,?)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID'], ($price / 2), $new_maxbet]);

                                echo feedback("Du er nå den stolte eier av blackjacken i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)), "success");
                            } else {
                                echo feedback("Du har ikke nok penger til å kjøpe blackjacken", "error");
                            }
                        } else {
                            echo feedback("Blackjacken er ikke til salgs", "error");
                        }
                    }
                }

                if (firma_on_marked(4, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>

                    <p class="description" style="margin: 0px;">Blackjacken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> ligger ute på <a href="?side=marked&id=5">marked</a></p>

                <?php } elseif (!blackjack_owner_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>
                    <p class="description">Det er ingen som eier blackjacken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?><br>
                        For å bli eier av blackjacken må du kjøpe den for <?php echo number($price); ?> kr. Halvparten av pengene vil gå i blackjack-banken til eieren. Dersom banken har under 5 000 000 kr vil den gå konkurs og vil komme ut for salg. Maks innsats bestemmes av eieren. <br>Dersom ingen eier en blackjack vil maks bet bli satt til <?php echo number($max_bet); ?> kr.</p>

                    <form method="post">
                        <input type="submit" name="buy_blackjack" value="Kjøp blackjacken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?>">
                    </form>
                    <?php } else {
                    if (blackjack_owner_incity(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {

                        if (isset($_POST['change_maxbet'])) {
                            $new_maxbet = $_POST['number_maxbet'];
                            $new_maxbet = remove_space($new_maxbet);

                            if (is_numeric($new_maxbet) && $new_maxbet >= 5000000 && $new_maxbet <= money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) && $max_bet != $new_maxbet && $new_maxbet <= 5000000000) {
                                $sql = "UPDATE blackjack_owner SET BJO_maksbet = ? WHERE BJO_owner = ? AND BJO_city = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$new_maxbet, $_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                echo feedback("Maksbet ble endret til " . number($new_maxbet), "success");
                            } else {
                                echo feedback("Ugyldig maksbet", "error");
                            }
                        }

                        if (isset($_POST['money_in'])) {
                            $money_in = $_POST['blackjack_bank_number'];
                            $money_in = remove_space($money_in);

                            if (is_numeric($money_in) && $money_in > 0 && $money_in <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                                take_money($_SESSION['ID'], $money_in, $pdo);

                                $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank + ? WHERE BJO_owner = ? AND BJO_city = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$money_in, $_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                echo feedback("Du satt inn " . number($money_in) . " kr i blackjack banken", "success");
                            } else {
                                echo feedback("Du kan ikke sette mer penger inn enn du har på hånden.", "error");
                            }
                        }

                        if (isset($_POST['money_out'])) {
                            $money_out = $_POST['blackjack_bank_number'];
                            $money_out = remove_space($money_out);

                            if ($money_out <= money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) && $money_out > 0) {
                                $sql = "UPDATE blackjack_owner SET BJO_bank = BJO_bank - ? WHERE BJO_owner = ? AND BJO_city = ?";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$money_out, $_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                give_money($_SESSION['ID'], $money_out, $pdo);

                                echo feedback("Du tok ut  " . number($money_out) . " kr fra blackjack banken", "success");
                            } else {
                                echo feedback("Du kan ikke ta ut mer penger enn du har på hånden.", "error");
                            }
                        }

                    ?>
                        <p class="description">Som eier av blackjacken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> er det ditt ansvar å få folk til å spille på blackjacken. Høy maksbet er noe som ilegges stor verdi for spillerne av Mafioso. Maksbet kan ikke være høyere enn du har i banken. Maksbet kan ikke være høyere enn penger i banken eller mer enn 5 000 000 000 kr</p>

                        <form method="post">
                            <div class="col-6" style="padding: 0 2.5px 0 0;">
                                <p>Maksbet: <?php echo number(get_maxbet(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?> kr</p>
                                <input type="text" id="number3" name="number_maxbet" placeholder="Beløp">
                                <input type="submit" name="change_maxbet" value="Endre maksbet">
                            </div>
                            <div class="col-6" style="padding: 0 0 0 2.5px;">
                                <p>Banken: <?php echo number(money_in_blackjack(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?> kr</p>
                                <input type="text" id="number2" name="blackjack_bank_number" placeholder="Beløp">
                                <input type="submit" name="money_in" value="Sett inn">
                                <input type="submit" name="money_out" value="Ta ut">
                            </div>
                            <div style="clear: both;"></div>
                        </form>
                    <?php } else { ?>
                        <span class="description">Eieren av blackjacken i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> er <?php echo ACC_username(blackjack_owner_incity(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo), $pdo); ?></span>
                <?php }
                } ?>
            <?php } ?>
        </div>
    </div>

    <style>
        .new_hr {
            border: 5px solid #121212;
        }

        .no-no-no:hover {
            cursor: no-drop;
        }

        #cards_container {
            padding-top: 20px;
            margin: 0 auto;
            text-align: center;
        }
    </style>

    <script>
        number_space("#number");
        number_space("#number2");
        number_space("#number3");
    </script>

<?php } ?>