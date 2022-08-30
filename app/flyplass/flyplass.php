<?php
if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    if (active_heist($_SESSION['ID'], $pdo)) {
        echo feedback("Du er i et aktivt heist i byen din og kan derfor ikke reise.", "blue");
    } else {

        $reset_waittime_payment = 2;
        $airport_price = 10000000;

        $reset_waittime = 5;
        $waittime = 90 + time();

        if (isset($_GET['reset'])) {
            if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) < $reset_waittime) {
                echo feedback("Du har ikke nok poeng", "error");
            } else {
                airport_reset_cd($_SESSION['ID'], $pdo);
                take_poeng($_SESSION['ID'], $reset_waittime, $pdo);
                echo feedback("Du har fjernet ventetiden for 5 poeng!", 'success');
            }
        }

        if (isset($_GET['solgt'])) {
            echo feedback("Flyplassen i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . " er solgt til staten", 'success');
        }

        if (isset($_GET['buy'])) {
            echo feedback("Du kjøpte flyplassen i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . "!", 'success');
        }

        if (isset($_GET['tooHighRisk'])) {
            echo feedback('Du ble tatt av politiet i Abu Dhabi og ble sendt til ' . city_name($_GET['tooHighRisk']) . '!', 'error');
        }

        if (isset($_POST['buy_airport'])) {
            if (airport_owner_already($_SESSION['ID'], $pdo)) {
                echo feedback('Du eier allerede en flyplass', 'error');
            } elseif (max_eiendeler($_SESSION['ID'], $pdo)) {
                echo feedback("Du har allerede 2 forskjellige eiendeler", 'fail');
            } elseif (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < $airport_price) {
                echo feedback("Du har ikke nok penger til å kjøpe flyplassen i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)), 'error');
            } elseif (airport_existence(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                echo feedback("Flyplassen i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . " er allerede eid av noen.", 'error');
            } elseif (airport_ownership($_SESSION['ID'], $pdo)) {
                $sql = "INSERT INTO flyplass (FP_city, FP_owner) VALUES (?, ?)";
                $pdo->prepare($sql)->execute([AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $_SESSION['ID']]);

                take_money($_SESSION['ID'], $airport_price, $pdo);

                header("Location: ?side=flyplass&buy");
            } else {
                echo feedback("Du er allerede eier av en flyplass.", 'error');
            }
        }

        if (isset($_GET['reise'])) {
            if ($_GET['reise'] == 5 && $activeDiamondEvent) {

                if (DE_session_row($_SESSION['ID'], 'DE_ban', $pdo) > time()) {
                    echo feedback('Du er banlyst fra Abu Dhabi til ' . time_to_text(DE_session_row($_SESSION['ID'], 'DE_ban', $pdo)), 'error');
                } elseif (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= 50000000) {

                    if (AS_session_row($_SESSION['ID'], 'AS_city', $pdo) == 5) {
                        echo feedback('Du er allerede i Abu Dhabi', 'error');
                    } else {
                        if (!DE_session_row($_SESSION['ID'], 'DE_acc_id', $pdo)) {
                            $sql = "INSERT INTO diamond_event (DE_acc_id) VALUES (?)";
                            $pdo->prepare($sql)->execute([$_SESSION['ID']]);
                        }

                        airport_give_cooldown($_SESSION['ID'], $waittime, $pdo);
                        take_money($_SESSION['ID'], 50000000, $pdo);
                        change_city($_SESSION['ID'], 5, $pdo);
                        header("Location: ?side=flyplass&velkommen_by=" . $_GET['reise'] . "");
                    }
                } else {
                    echo feedback('Du har ikke nok penger for å reise til Abu Dhabi!', 'error');
                }
            } else {
                user_log($_SESSION['ID'], $side, "Reiser", $pdo);
                $legal = array(0, 1, 2, 3, 4);
                if (!in_array($_GET['reise'], $legal) || !is_numeric($_GET['reise'])) {
                    user_log($_SESSION['ID'], $side, "Ugyldig verdi: " . $legal, $pdo);
                    echo feedback("Ugyldig verdi.", "fail");
                } elseif (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < city_price($_GET['reise'], $pdo)) {
                    user_log($_SESSION['ID'], $side, "Ikke nok penger", $pdo);
                    echo feedback("Du har ikke råd til å reise til " . city_name($_GET['reise']), "error");
                } elseif ($_GET['reise'] == AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) {
                    user_log($_SESSION['ID'], $side, "Allerede i samme by", $pdo);
                    echo feedback("Du er allerede i " . city_name($_GET['reise']), "error");
                } elseif (airport_ready($_SESSION['ID'], $pdo)) {
                    user_log($_SESSION['ID'], $side, "Ventetid", $pdo);
                    echo feedback("Du må vente.", "error");
                } else {
                    airport_give_cooldown($_SESSION['ID'], $waittime, $pdo);
                    take_money($_SESSION['ID'], city_price($_GET['reise'], $pdo), $pdo);
                    change_city($_SESSION['ID'], $_GET['reise'], $pdo);

                    if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 44) {
                        mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                    }

                    if (airport_existence($_GET['reise'], $pdo)) {
                        $owner_id = airport_owner($_GET['reise'], $pdo);
                        give_airport_money($owner_id, city_price($_GET['reise'], $pdo), $pdo);
                        update_bedrift_inntekt($_SESSION['ID'], 0, $_GET['reise'], city_price($_GET['reise'], $pdo), $pdo);
                    }

                    user_log($_SESSION['ID'], $side, "Reiser til " . city_name($_GET['reise']), $pdo);
                    header("Location: ?side=flyplass&velkommen_by=" . $_GET['reise'] . "");
                }
            }
        }

        if (isset($_GET['velkommen_by'])) {
            if ($_GET['velkommen_by'] == 5 && $activeDiamondEvent) {
                echo feedback('Velkommen til Abu Dhabi!', 'airport');
            } else {
                $legal = array(0, 1, 2, 3, 4);
                if (!in_array($_GET['velkommen_by'], $legal) || !is_numeric($_GET['velkommen_by'])) {
                    echo feedback("Ugyldig verdi.", "fail");
                } else {
                    echo feedback("Velkommen til " . city_name($_GET['velkommen_by']), "success");
                }
            }
        }

        if (airport_ready($_SESSION['ID'], $pdo)) {
            $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $time_left = $row['CD_airport'] - time();

            $minutes = floor($time_left / 60);
            $seconds = $time_left - $minutes * 60;

            echo feedback('Du har nylig vært på en flyvetur. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> sekunder før du kan fly igjen. <a href="?side=flyplass&reset" style="font-weight: bolder;">Du kan resette fly-tiden for ' . number($reset_waittime) . ' poeng ved å trykke her</a>', 'cooldown'); ?>
            <br>
            <script>
                timeleft(<?php echo $time_left; ?>, "countdowntimer");
            </script>

        <?php

        } else {

        ?>
            <div class="col-10 single" style="margin-top: -10px;">
                <?php

                for ($i = 0; $i < 5; $i++) {

                ?>
                    <div class="col-4">
                        <div class="content">
                            <img class="action_image" src="img/city/<?php echo $i; ?>.png">
                            <ul>
                                <li>Byskatt: <?php echo output_city_tax(get_city_tax($i, $pdo)); ?></li>
                                <li>Eier av flyplass:
                                    <?php if (airport_existence($i, $pdo)) {
                                        echo ACC_username(airport_owner($i, $pdo), $pdo);
                                    } else {
                                        echo '<span class="description">Ingen</span>';
                                    } ?>
                                </li>
                                <li>Territorium:
                                    <?php if (get_city_owner($i, $pdo)) {
                                        echo '<a href="?side=familieprofil&id=' . get_city_owner($i, $pdo) . '">' . get_familyname(get_city_owner($i, $pdo), $pdo) . '</a>';
                                    } else {
                                        echo '<span class="description">Ingen</span>';
                                    } ?>
                                </li>
                            </ul>
                            <h4>Pris: <?php echo number(city_price($i, $pdo)); ?> kr</h4>
                            <br>
                            <a href="?side=flyplass&reise=<?php echo $i; ?>" class="a_as_button_secondary">Fly til <?php echo city_name($i); ?></a>
                            <br><br>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($activeDiamondEvent) { ?>
                    <?php if (DE_session_row($_SESSION['ID'], 'DE_ban', $pdo) <= time()) { ?>
                        <div class="col-4">
                            <div class="content">
                                <img class="action_image" src="img/city/5.png">
                                <ul>
                                    <li>Finn diamanter i gruvene i Abu Dhabi</li>
                                    <li>Eier av flyplass: Sjeiken
                                    </li>
                                    <li>Territorium: Sjeiken
                                    </li>
                                </ul>
                                <h4>Pris: 50 000 000 kr</h4>
                                <br>
                                <a href="?side=flyplass&reise=5" class="a_as_button_secondary">Fly til Abu Dhabi</a>
                                <br><br>
                            </div>
                        </div>
                <?php } else {
                        echo feedback('Du er bannlyst fra Abu Dhabi til ' . date_to_text(DE_session_row($_SESSION['ID'], 'DE_ban', $pdo)), 'error');
                    }
                } ?>
                <div class="col-4">
                    <div class="content">
                        <?php

                        if (airport_existence(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>

                            Flyplassen i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> er eid av
                            <a href="?side=profil&id=<?php echo airport_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo); ?>">
                                <?php echo ACC_username(airport_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo), $pdo);
                                ?></a>
                            <?php

                            $money_for_sell = 10000000;

                            if (airport_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {
                                if (isset($_POST['sell_airport'])) {
                                    give_money($_SESSION['ID'], $money_for_sell, $pdo);

                                    $sql = "DELETE FROM flyplass WHERE FP_owner = " . $_SESSION['ID'] . " LIMIT 1";
                                    $pdo->exec($sql);

                                    header("location: ?side=flyplass&solgt");
                                }

                                $min_price = 5000;
                                $max_price = 1000000;

                                if (isset($_POST['change_price']) && isset($_POST['new_price'])) {
                                    $new_price = remove_space($_POST['new_price']);

                                    if (is_numeric($new_price)) {
                                        if ($new_price >= $min_price && $new_price <= $max_price) {
                                            edit_city_price(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $new_price, $pdo);

                                            echo feedback("Prisen er endret til " . number($new_price) . "kr", "success");
                                        } else {
                                            echo feedback("Prisen kan ikke være mindre enn " . number($min_price) . " eller mer enn " . number($max_price), "error");
                                        }
                                    } else {
                                        echo feedback("Ugyldig input", "error");
                                    }
                                }


                                if (isset($_POST['money_out'])) {
                                    user_log($_SESSION['ID'], $side, "Tar ut penger fra flyplass", $pdo);
                                    $money_in_airport = airport_money($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);

                                    if ($money_in_airport == 0 || $money_in_airport == null) {
                                        user_log($_SESSION['ID'], $side, "Ingen penger å ta ut", $pdo);
                                        echo feedback("Ingen penger å ta ut", "error");
                                    } else {
                                        $sql = "UPDATE flyplass SET FP_money = 0 WHERE FP_owner = ? AND FP_city = ?";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->execute([$_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                        give_money($_SESSION['ID'], $money_in_airport, $pdo);

                                        user_log($_SESSION['ID'], $side, "Tar ut: " . $money_in_airport . "kr", $pdo);
                                        echo feedback("Du tok ut " . number($money_in_airport) . " kr fra flyplass banken", "success");
                                    }
                                }
                            ?>
                                <form method="post">
                                    <p class="description">Penger i flybanken: <?php echo number(airport_money($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?> kr</p>
                                    <input type="submit" name="money_out" value="Ta ut alle pengene"><br><br>
                                    <p class="description">Endre fly-pris til <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?>: </p>
                                    <input style="width: 70%;" id="number" type="text" name="new_price" placeholder="Ny pris">
                                    <input type="submit" name="change_price" value="Endre"><br>
                                    <p class="description">Minstepris: <?php echo number($min_price); ?> kr<br>
                                        Max pris: <?php echo number($max_price); ?> kr</p>
                                    <p class="description">Flyplassen er eid av deg og kan selges for <?php echo number($money_for_sell); ?> kr til staten.</p>
                                    <input type="submit" name="sell_airport" value="Selg flyplass">
                                </form>
                            <?php } ?>
                            <br>
                            <?php } else {
                            if (!firma_on_marked(0, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>
                                <p class="description" style="margin-top: 0px;">Flyplassen i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> er ikke eid av noen. Du kan kjøpe den for <?php echo number($airport_price); ?> kr. Som eier av flyplass vil du velge reiseprisen selv og få disse pengene på din flyplass-bank.</p>
                                <form method="post">
                                    <input type="submit" name="buy_airport" value="Kjøp flyplass">
                                </form>

                            <?php } else { ?>
                                <p class="description" style="margin: 0px;">Flyplassen i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> ligger ute på <a href="?side=marked&id=5">marked</a></p>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <script>
            number_space("#number");
        </script>
<?php }
} ?>