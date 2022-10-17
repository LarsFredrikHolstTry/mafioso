<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    if (isset($_GET['selg_alle'])) {
        if (!is_numeric($_GET['selg_alle'])) {
            echo feedback("Ugyldig verdi", "error");
        } else {
            echo feedback("Du solgte ting for " . number($_GET['selg_alle']) . " kr", "success");
        }
    }

    if (isset($_POST['sell_all'])) {
        user_log($_SESSION['ID'], $_GET['side'], "Selger alle ting", $pdo);

        if ($things_in_storage == 0) {
            echo feedback("Du har ingen ting å selge", "error");
        } else {
            $money_amount = value_all_things($_SESSION['ID'], $pdo);

            $money_amount_bank = $money_amount * 0.1;
            $money_amount_give = $money_amount * 0.9;

            if (active_superhelg($pdo)) {
                $money_amount_give = $money_amount_give * 2;
            }

            give_money($_SESSION['ID'], $money_amount_give, $pdo);

            $sql = "DELETE FROM things WHERE TH_acc_id = " . $_SESSION['ID'] . " AND TH_type NOT IN (3, 18, 19, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39)";
            $pdo->exec($sql);

            user_log($_SESSION['ID'], $_GET['side'], "Selger alle ting for: " . number($money_amount_give) . " kr", $pdo);
            header("Location: ?side=lager&selg_alle=" . $money_amount_give . "");
        }
    }

    if (isset($_POST['sell_chosen'])) {
        user_log($_SESSION['ID'], $_GET['side'], "Selger valgt ting", $pdo);

        $money = 0;
        $amount_of_thing = 0;
        $things_sold = false;

        foreach ($_POST['values'] as $data) {
            if ($data['amount'] == 0) {
            } else {
                if ($data['amount'] > amount_of_things($_SESSION['ID'], $data['thing_id'], $pdo)) {
                    echo feedback("Ugyldig antall ting", "fail");
                    $things_sold = false;
                    break;
                } else {
                    for ($j = 0; $j < $data['amount']; $j++) {
                        $money = $money + thing_price($data['thing_id']);
                        $amount_of_thing++;

                        $sql = "DELETE FROM things WHERE TH_acc_id = " . $_SESSION['ID'] . " AND TH_type = " . $data['thing_id'] . " LIMIT 1";
                        $pdo->exec($sql);
                    }
                    $things_sold = true;
                }
            }
        }

        if ($things_sold) {
            $money_amount_bank = $money * 0.1;
            $money_amount_give = $money * 0.9;

            if (active_superhelg($pdo)) {
                $money_amount_give = $money_amount_give * 2;
            }

            give_money($_SESSION['ID'], $money_amount_give, $pdo);

            user_log($_SESSION['ID'], $_GET['side'], "solgte " . $amount_of_thing . " ting for " . number($money_amount_give) . " kr", $pdo);

            echo feedback("Du solgte " . $amount_of_thing . " ting for " . number($money_amount_give) . " kr", "success");
        }
    }

    if (isset($_GET['bonus'])) {
        $legal_bonus = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);

        if (isset($_POST[0])) {
            $id = 29;
            if (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                use_energy_drink($_SESSION['ID'], $pdo);
            } else {
                echo feedback("Du har ingen energidrikker", "error");
            }
        } elseif (isset($_POST[1])) {
            $id = 30;
            if (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $chance = mt_rand(0, 100);

                if ($chance >= 0 && $chance <= 40) {
                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet kiste: tom", $pdo);
                    echo feedback("Kisten var tom, du fant ingenting", "fail");
                } elseif ($chance >= 40 && $chance <= 60) {
                    $min = 100000000;     // 100 000 000
                    $max = 500000000;    // 500 000 000
                    $money_outcome = mt_rand($min, $max);

                    give_money($_SESSION['ID'], $money_outcome, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet kiste: " . number($money_outcome) . " kr", $pdo);
                    echo feedback("Du åpnet kisten og fant " . number($money_outcome) . " kr", "success");
                } elseif ($chance >= 60 && $chance <= 80) {
                    $bullets = 100;     // 100
                    give_bullets($_SESSION['ID'], $bullets, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet kiste: " . number($bullets) . " kuler", $pdo);
                    echo feedback("Du åpnet kisten og fant " . number($bullets) . " kuler", "success");
                } elseif ($chance >= 80 && $chance <= 90) {
                    $poeng = 100;

                    give_poeng($_SESSION['ID'], $poeng, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet kiste: " . number($poeng) . " poeng", $pdo);
                    echo feedback("Du åpnet kisten og fikk " . $poeng . " poeng", "success");
                } elseif ($chance >= 90 && $chance <= 100) {
                    $min = 10000;
                    $max = 100000;
                    $forsvar_outcome = mt_rand($min, $max);

                    give_fp($forsvar_outcome, $_SESSION['ID'], $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet kiste: " . number($forsvar_outcome) . " forsvar", $pdo);
                    echo feedback("Du åpnet kisten og fant " . number($forsvar_outcome) . " forsvar", "success");
                }

                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 49) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);
            } else {
                user_log($_SESSION['ID'], $_GET['side'], "Ingen kister", $pdo);
                echo feedback("Du har ingen kister", "error");
            }
        } elseif (isset($_POST[2])) {
            $id = 3;
            if (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                if (mt_rand(0, 3) != 1) {
                    $weed_outcome = mt_rand(1, 6);

                    give_weed($_SESSION['ID'], $weed_outcome, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], "Cannabis plante klippet: " . number($weed_outcome) . " g", $pdo);

                    echo feedback("Du klippet planten og fikk " . number($weed_outcome) . " g cannabis ut av den", "success");
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], "Cannabis plante: døde", $pdo);

                    echo feedback("Planten du skulle klippe tørket ut.", "error");
                }

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);
            } else {
                user_log($_SESSION['ID'], $_GET['side'], "Ingen hasjplanter", $pdo);
                echo feedback("Du har ingen hasjplanter", "fail");
            }
        } elseif (isset($_POST[3])) {
            $id = 18;
            if (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                if (mt_rand(0, 1) == 1) {
                    $money_outcome = mt_rand(25000, 1500000);

                    give_money($_SESSION['ID'], $money_outcome, $pdo);
                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet visakort: " . number($money_outcome) . " kr", $pdo);
                    echo feedback("Du fikk " . number($money_outcome) . " kr", "success");
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], "Åpnet visakort: feilet", $pdo);
                    echo feedback("Du feilet og fikk ingen penger.", "error");
                }

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);
            } else {
                user_log($_SESSION['ID'], $_GET['side'], "Ingen visakort", $pdo);
                echo feedback("Du har ingen visakort", "fail");
            }
        } elseif (isset($_POST[4])) {
            $id = 19;
            if (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                if (mt_rand(0, 1) == 1) {
                    $money_outcome = mt_rand(25000, 1500000);

                    give_money($_SESSION['ID'], $money_outcome, $pdo);
                    echo feedback("Du fikk " . number($money_outcome) . " kr", "success");
                } else {
                    echo feedback("Du feilet og fikk ingen penger.", "error");
                }

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);
            } else {
                echo feedback("Du har ingen mastercard", "fail");
            }
        } elseif (isset($_POST[5])) {
            $id = 32;
            $my_family_id = get_my_familyID($_SESSION['ID'], $pdo);

            if ($my_family_id == null) {
                echo feedback("Du er ikke medlem i en familie", "error");
            } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $sql = "SELECT FAMMEM_acc_id FROM family_member WHERE FAMMEM_fam_id = " . $my_family_id . "";
                $stmt = $pdo->query($sql);
                while ($row_memb = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $text = username_plain($_SESSION['ID'], $pdo) . " har aktivert en energidrikk for familien din!";
                    send_notification($row_memb['FAMMEM_acc_id'], $text, $pdo);

                    $sql = "DELETE FROM double_xp WHERE DX_acc_id = " . $row_memb['FAMMEM_acc_id'] . "";
                    $pdo->exec($sql);

                    give_double_exp($row_memb['FAMMEM_acc_id'], $pdo);
                }

                $sql = "DELETE FROM things WHERE TH_type = 32 AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);
            } else {
                echo feedback("Du har ingen familie energidrikker", "fail");
            }
        } elseif (isset($_POST[6])) {
            $id = 31;
            $chance = mt_rand(0, 100);
            if (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {

                if ($chance < 40) {
                    // tomt
                    echo feedback("Gresskaret var tomt", "error");
                }
                if ($chance >= 40 && $chance < 50) {
                    // Hemmelig kiste
                    $thing_id = 30;
                    update_things($_SESSION['ID'], $thing_id, $pdo);

                    echo feedback('Du fant en hemmelig kiste!', 'success');
                } elseif ($chance >= 50 && $chance < 58) {
                    // 10-100 exp
                    $min = 10;
                    $max = 100;
                    $amount = mt_rand($min, $max);

                    give_exp($_SESSION['ID'], $amount, $pdo);
                    check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                    echo feedback('Du fikk ' . number($amount) . ' EXP!', 'success');
                } elseif ($chance >= 58 && $chance < 70) {
                    // Random bil
                    $min = 0;
                    $max = 19;

                    $car_id = mt_rand($min, $max);
                    $city_id = mt_rand(0, 4);

                    update_garage($_SESSION['ID'], $car_id, $city_id, $pdo);

                    echo feedback('Du fant en ' . car($car_id) . ' fra ' . city_name($city_id) . '!', 'success');
                } elseif ($chance >= 70 && $chance < 80) {
                    // Random ting
                    $min = 0;
                    $max = 32;

                    $thing_id = mt_rand($min, $max);

                    update_things($_SESSION['ID'], $thing_id, $pdo);

                    echo feedback('Du fant en ' . thing($thing_id) . '!', 'success');
                } elseif ($chance >= 80 && $chance < 90) {
                    // Energidrikk
                    $thing_id = 29;
                    update_things($_SESSION['ID'], $thing_id, $pdo);

                    echo feedback('Du fant en energidrikk!', 'success');
                } elseif ($chance >= 90 && $chance < 95) {
                    // Hemmelig kiste
                    $thing_id = 30;
                    update_things($_SESSION['ID'], $thing_id, $pdo);

                    echo feedback('Du fant en hemmelig kiste!', 'success');
                } elseif ($chance >= 95 && $chance <= 100) {
                    // penger mellom 5 mill og 20 mill
                    $min = 5000000;
                    $max = 20000000;
                    $amount = mt_rand($min, $max);

                    give_money($_SESSION['ID'], $amount, $pdo);

                    echo feedback('Du fant ' . number($amount) . ' kr!', 'success');
                }

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);
            } else {
                echo feedback("Du har ingen gresskar", "fail");
            }
        } elseif (isset($_POST[7])) {
            $id = 34;
            if (active_superhelg($pdo)) {
                echo feedback('Det er allerede en aktiv happy hour', 'fail');
            } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $title = "Happy hour 1 time";
                $sql = "INSERT INTO super_helg (SHELG_start, SHELG_end, SHELG_title) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([time(), time() + 3600, $title]);

                $text = " startet 1 time happy hour!";
                $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], $text, time()]);

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);

                echo feedback('Du har aktivert 1 time med happy hour', 'success');
            } else {
                echo feedback('Du har ikke valgt ting', 'fail');
            }
        } elseif (isset($_POST[8])) {
            $id = 35;
            if (active_superhelg($pdo)) {
                echo feedback('Det er allerede en aktiv happy hour', 'fail');
            } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $title = "Happy hour 3 timer";
                $sql = "INSERT INTO super_helg (SHELG_start, SHELG_end, SHELG_title) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([time(), time() + (3600 * 3), $title]);

                $text = " startet 3 timer happy hour!";
                $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], $text, time()]);

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);

                echo feedback('Du har aktivert 3 time med happy hour', 'success');
            } else {
                echo feedback('Du har ikke valgt ting', 'fail');
            }
        } elseif (isset($_POST[9])) {
            $id = 36;
            if (active_superhelg($pdo)) {
                echo feedback('Det er allerede en aktiv happy hour', 'fail');
            } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $title = "Happy hour 6 timer";
                $sql = "INSERT INTO super_helg (SHELG_start, SHELG_end, SHELG_title) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([time(), time() + (3600 * 6), $title]);

                $text = " startet 6 timer happy hour!";
                $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], $text, time()]);

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);

                echo feedback('Du har aktivert 6 timer med happy hour', 'success');
            } else {
                echo feedback('Du har ikke valgt ting', 'fail');
            }
        } elseif (isset($_POST[10])) {
            $id = 37;
            if (active_superhelg($pdo)) {
                echo feedback('Det er allerede en aktiv happy hour', 'fail');
            } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $title = "Happy hour 12 timer";
                $sql = "INSERT INTO super_helg (SHELG_start, SHELG_end, SHELG_title) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([time(), time() + (3600 * 12), $title]);

                $text = " startet 12 timer happy hour!";
                $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], $text, time()]);

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);

                echo feedback('Du har aktivert 12 timer med happy hour', 'success');
            } else {
                echo feedback('Du har ikke valgt ting', 'fail');
            }
        } elseif (isset($_POST[11])) {
            $id = 38;
            if (active_superhelg($pdo)) {
                echo feedback('Det er allerede en aktiv happy hour', 'fail');
            } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
                $title = "Happy hour 24 timer";
                $sql = "INSERT INTO super_helg (SHELG_start, SHELG_end, SHELG_title) VALUES (?,?,?)";
                $pdo->prepare($sql)->execute([time(), time() + (3600 * 24), $title]);

                $text = " startet 24 timer happy hour!";
                $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_SESSION['ID'], $text, time()]);

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                $pdo->exec($sql);

                echo feedback('Du har aktivert 24 timer med happy hour', 'success');
            } else {
                echo feedback('Du har ikke valgt ting', 'fail');
            }
        } elseif (isset($_POST[12])) {
            $id = 39;
            $total_diamonds = amount_of_things($_SESSION['ID'], $id, $pdo);
            if ($total_diamonds > 0) {
                $sql = "UPDATE diamond_event SET DE_diamonds = DE_diamonds + ? WHERE DE_acc_id = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$total_diamonds, $_SESSION['ID']]);

                $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'];
                $pdo->exec($sql);

                give_money($_SESSION['ID'], ($total_diamonds * 100000), $pdo);

                echo feedback('Du har solgt ' . number($total_diamonds) . ' diamanter til sjeiken for ' . number($total_diamonds * 100000) . ' kr', 'success');
            } else {
                echo feedback('Du har ikke valgt ting', 'fail');
            }
        }

        $bonus_type[0] = "Bruk energidrikk";
        $bonus_name[0] = "Energidrikk er en boost som gir deg dobbel exp i 3 timer. Du kan enten velge å åpne den selv eller selge den videre. Når bonusen er brukt så kan den ikke angres.";

        $bonus_type[1] = "Åpne kisten";
        $bonus_name[1] = "En hemmelig kiste kan åpnes for å få diverse belønninger. Det er ingen som vet hvor kisten kommer fra eller hva den inneholder. Du kan enten åpne den selv eller selge den videre. Når kisten er åpnet kan det ikke angres.";

        $bonus_type[2] = "Klipp hasjplanten";
        $bonus_name[2] = "Ved å klippe en hasjplante har du 2 utfall, enten klipper du planten og får mellom 1 og 6 gram cannabis, eller så tørker den ut og du får ingen gram.";

        $bonus_type[3] = "Hack visakort";
        $bonus_name[3] = "Du har funnet et visakort! Du kan hacke det for å få pengene som er på visakortet til din konto. Du har 50% sjanse for å få pengene over.";

        $bonus_type[4] = "Hack Mastercard";
        $bonus_name[4] = "Du har funnet et mastercard! Du kan hacke det for å få pengene som er på mastercardet til din konto. Du har 50% sjanse for å få pengene over.";

        $bonus_type[5] = "Familie energidrikk";
        $bonus_name[5] = "Familie energidrikk vil aktivere energidrikk hos alle dine familiemedlemmer. Dersom en i familien allerede har en energidrikk vil den bli erstattet med din.";

        $bonus_type[6] = "Gresskar";
        $bonus_name[6] = "Åpne gresskar for å se hva påskeharen har gjemt unna til deg!";

        $bonus_type[7] = "Happy hour 1 time";
        $bonus_name[7] = "Aktiver for å starte 1 time med happy hour for hele spillet!";

        $bonus_type[8] = "Happy hour 3 timer";
        $bonus_name[8] = "Aktiver for å starte 3 timer med happy hour for hele spillet!";

        $bonus_type[9] = "Happy hour 6 timer";
        $bonus_name[9] = "Aktiver for å starte 6 timer med happy hour for hele spillet!";

        $bonus_type[10] = "Happy hour 12 timer";
        $bonus_name[10] = "Aktiver for å starte 12 timer med happy hour for hele spillet!";

        $bonus_type[11] = "Happy hour 24 timer";
        $bonus_name[11] = "Aktiver for å starte 24 timer med happy hour for hele spillet!";

        $bonus_type[12] = "Gi diamanter til sjeiken";
        $bonus_name[12] = "Gi alle dine diamanter til sjeiken for 100 000kr per diamant";


        if (in_array($_GET['bonus'], $legal_bonus)) {
?>
            <div class="col-9 single" style="padding-bottom: 5px;">
                <div class="content">
                    <form method="post">
                        <p class="description"><?php echo $bonus_name[$_GET['bonus']]; ?></p>
                        <input type="submit" name="<?php echo $_GET['bonus']; ?>" value="<?php echo $bonus_type[$_GET['bonus']]; ?>">
                    </form>
                </div>
            </div>

    <?php
        } else {
            echo feedback("Ugyldig side", "error");
        }
    }

    ?>
    <div class="col-12 single">
        <div class="col-8" style="padding-top: 0px;">
            <div class="content">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                <p class="description">Her vil du se dine ting du har stjelt fra brekk. Disse kan enten selges for verdien eller brukes i oppdrag. Noen av tingene har en spesial-funksjon som kan gi deg en ekstra boost.</p>
                <br>
                <form method="post" class="table_check">
                    <table id="datatable" data-order='[[0, "asc"], [2, "desc"]]' data-paging="false">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Ting</th>
                                <th style="width: 20%;">Antall</th>
                                <th style="width: 15%;">Verdi</th>
                                <th style="width: 5%;">Antall</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $i = 0;

                            $sql = "SELECT DISTINCT TH_type FROM things WHERE TH_acc_id = " . $_SESSION['ID'] . " ORDER BY TH_type DESC";
                            $stmt = $pdo->query($sql);
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                            ?>
                                <tr>
                                    <td>
                                        <?php
                                        $thing = thing($row['TH_type']);
                                        $thing_price = thing_price($row['TH_type']);
                                        if ($row['TH_type'] == 29) /* Energidrikk */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=0">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 30) /* Hemmelig kiste */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=1">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 3) /* hasjplante */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=2">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 18) /* Visakort */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=3">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 19) /* Mastercard */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=4">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 32) /* Familie energidrikk */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=5">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 31) /* Gresskar */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=6">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 34) /* HH 1t */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=7">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 35) /* HH 3t */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=8">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 36) /* HH 6t */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=9">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 37) /* HH 12t */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=10">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 38) /* HH 24t */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=11">' . $thing . '</a>';
                                        } elseif ($row['TH_type'] == 39) /* Diamant */ {
                                            echo '<span style="display:none">$pecial item</span><a style="color: var(--button-bg-color);" href="?side=lager&bonus=12">' . $thing . '</a>';
                                        } else {
                                            echo $thing;
                                        }

                                        ?></td>
                                    <td><?php echo amount_of_things($_SESSION['ID'], $row['TH_type'], $pdo); ?></td>
                                    <td data-order="<?php echo $thing_price; ?>"><?php echo number($thing_price); ?> kr</td>
                                    <td>
                                        <input type="hidden" style="margin: 0; padding: 0;" name="values[<?php echo $i; ?>][thing_id]" value="<?php echo $row['TH_type']; ?>">
                                        <input type="number" style="width: 50px; margin: 0;" name="values[<?php echo $i; ?>][amount]" placeholder="0" min="0">
                                    </td>
                                </tr>
                            <?php $i++;
                            } ?>
                        </tbody>
                    </table>
                    <div>
                        <p style="float: left;" class="description"><a href="?side=poeng"><?php echo $things_in_storage ?> av <?php echo $max_storage; ?> plasser brukt <b><a href="?side=poeng">Oppgrader</a></b></a></p>
                        <p style="float: right;" class="description">Total verdi:
                            <?php

                            $value = value_all_things($_SESSION['ID'], $pdo);

                            if (active_superhelg($pdo)) {
                                $value = $value * 2;
                            } else {
                                $value = $value;
                            }

                            echo number($value);

                            ?> kr</p>
                        <div style="clear: both;"></div>
                    </div>

                    <?php

                    if (!things_exist($_SESSION['ID'], $pdo)) {
                        echo feedback("Du har ingen ting å lagre i lageret ditt. Gjør <a href='?side=brekk'>brekk</a> for å stjele ting.", "blue");
                    } else {
                    ?>

                        <input type="submit" name="sell_all" value="Selg alle" onclick="return confirm('Er du sikker på at du ønsker å selge alle tingene?');">
                        <input type="submit" name="sell_chosen" value="Selg valgte" style="float: right;">

                    <?php
                    }
                    ?>
                </form>
            </div>
        </div>
        <div class="col-4" style="padding-top: 0px;">
            <?php

            $beskyttelse[0] = "Alarmsystem";
            $beskyttelse[1] = "Premium alarmsystem";
            $beskyttelse[2] = "Ståldører";
            $beskyttelse[3] = "Nedgravet lager";
            $beskyttelse[4] = "Livvakter";
            $beskyttelse[5] = "Premium livvakt";

            $beskyttelse_pris[0] = 0;
            $beskyttelse_pris[1] = 10000000;
            $beskyttelse_pris[2] = 50000000;
            $beskyttelse_pris[3] = 100000000;
            $beskyttelse_pris[4] = 250000000;
            $beskyttelse_pris[5] = 100;

            $stmt = $pdo->prepare("SELECT BESK_ting FROM beskyttelse WHERE BESK_acc_id = :id");
            $stmt->execute(['id' => $_SESSION['ID']]);
            $row_lager = $stmt->fetch();


            if (isset($_GET['upgraded'])) {
                echo feedback("Du kjøpte " . $beskyttelse[$_GET['upgraded']], "success");
            }

            if (isset($_GET['upgrade'])) {
                $legal = array(0, 1, 2, 3, 4, 5);
                $upgrade_type = $_GET['upgrade'];

                if (in_array($upgrade_type, $legal)) {
                    if (!$row_lager) {
                        $sql = "INSERT INTO beskyttelse (BESK_acc_id) VALUES (?)";
                        $pdo->prepare($sql)->execute([$_SESSION['ID']]);
                    }

                    if ($row_lager && $upgrade_type <= $row_lager['BESK_ting']) {
                        echo feedback("Du har allerede " . $beskyttelse[$upgrade_type] . "", "error");
                    } elseif ($upgrade_type - 1 != $row_lager['BESK_ting']) {
                        echo feedback("Du må ha " . $beskyttelse[$row_lager['BESK_ting'] + 1] . " før du kan oppgradere", "error");
                    } elseif (
                        $beskyttelse_pris[$upgrade_type] != 5 && $beskyttelse_pris[$upgrade_type] > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)
                        ||
                        $upgrade_type == 5 && $beskyttelse_pris[$upgrade_type] > AS_session_row($_SESSION['ID'], 'AS_points', $pdo)
                    ) {
                        echo feedback("Du har ikke nok penger / poeng til å kjøpe " . $beskyttelse[$upgrade_type], "error");
                    } else {
                        $sql = "UPDATE beskyttelse SET BESK_ting = (BESK_ting + 1) WHERE BESK_acc_id='" . $_SESSION['ID'] . "'";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        if ($upgrade_type != 5) {
                            take_money($_SESSION['ID'], $beskyttelse_pris[$upgrade_type], $pdo);
                        } else {
                            take_poeng($_SESSION['ID'], $beskyttelse_pris[$upgrade_type], $pdo);
                        }

                        header("location: ?side=lager&upgraded=" . $upgrade_type . "");
                    }
                }
            }

            $activated = "img/icons/check-mark.svg";
            $not_activated = "img/icons/cancel.svg";

            ?>
            <div class="content">
                <h4>Beskyttelse</h4>
                <p class="description">Beskyttelse er viktig for å unngå at folk raner ting fra deg. Desto flere sikkerhetstiltak du gjør, desto vanskeligere er det å rane ting fra deg.</p>
                <p>Sjanse for å bryte seg inn i ditt lager:

                    <?php

                    $total_beskyttelse = stjel_sjanse_beskyttelse($_SESSION['ID'], 'lager', $pdo);

                    if ($total_beskyttelse <= 10) {
                        echo '<span style="color: orange;">';
                    } elseif ($total_beskyttelse >= 11 && $total_beskyttelse <=  30) {
                        echo '<span style="color: yellow;">';
                    } elseif ($total_beskyttelse >= 31 && $total_beskyttelse <=  60) {
                        echo '<span style="color: lightgreen;">';
                    } elseif ($total_beskyttelse >= 61 && $total_beskyttelse <=  89) {
                        echo '<span style="color: green;">';
                    } elseif ($total_beskyttelse >= 91) {
                        echo '<span style="color: white;">';
                    }

                    echo 100 - $total_beskyttelse;
                    echo '%</span>';


                    ?>

                </p>
                <table>
                    <tr>
                        <th style="width: 5%;"></th>
                        <th style="width: 30%;">Type</th>
                        <th style="width: 20%;">Pris</th>
                    </tr>
                    <?php for ($i = 0; $i < count($beskyttelse); $i++) { ?>
                        <tr class="cursor_hover clickable-row" data-href="?side=lager&upgrade=<?php echo $i; ?>">
                            <td><img style="max-width: 21px;" src="<?php if ($row_lager && $row_lager['BESK_ting'] < $i) {
                                                                        echo $not_activated;
                                                                    } else {
                                                                        echo $activated;
                                                                    } ?>"></td>
                            <td><?php echo $beskyttelse[$i]; ?></td>
                            <td><?php if ($i == 0) {
                                    echo 'gratis';
                                } else {
                                    echo number($beskyttelse_pris[$i]);
                                } ?> <?php if ($i == 0) {
                                        } elseif ($i == 5) {
                                            echo ' poeng';
                                        } else {
                                            echo ' kr';
                                        } ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <script>
        $('.table_check table tr').each((i, e) => {
            if (i === 0) {
                let checkAll = $('<th style="text-align: right;"><label class="label_container" style="display: initial;"><input type="checkbox"><span class="checkmark"></span></label></th>');

                checkAll.on('change', e => {
                    let allCheked = $(e.target).prop('checked');

                    $('.row_checked').each((i, e) => {
                        if ($(e).prop('checked') && !allCheked || !$(e).prop('checked') && allCheked) {
                            $(e).click();
                        }
                    });
                });

                $(e).append(checkAll);
                return;
            }

            let rowChecbox = $('<td style="text-align: right;"><label class="label_container" style="display: initial;"><input type="checkbox" class="row_checked"><span class="checkmark"></label></td>');

            rowChecbox.on('change', e => {
                let target = $(e.target);
                let row = target.parents('tr');
                let num_input = row.find('input[type="number"]');
                let num_in_storage = parseInt(row.find('td:nth-of-type(2)').text());
                let isChecked = target.prop('checked');

                if (!isChecked) {
                    num_input.val(0);
                    return;
                }

                num_input.val(num_in_storage);
            });

            $(e).append(rowChecbox);
        });

        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                $(".clickable-row").unbind("click"); //Fjern click-event-listener for alle .clickable-row's
                window.location = $(this).data("href");
            });

            $('#datatable').DataTable();
        });
    </script>
    <script type="text/javascript" src="includes/datatable/datatables.min.js"></script>
<?php } ?>