<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    $private_track = 10000;
    $waittime = 30 + time();
    $waittime_race = 45 + time();

    $legal = array('drift', 'drag', 'race');
    $categories = array('drifting', 'dragrace', 'kappløp');

    $exp = 1;
    $pr_training = 1;

    if (isset($_GET['sold'])) {
        echo feedback('Du solgte den private kjørebanen for 10 000 000 kr', 'success');
    }

    if (isset($_GET['trained'])) {
        echo feedback('Du trente deg i ' . $_GET['trained'] . '', 'success');
    }

    if (isset($_GET['notenoughmoney'])) {
        echo feedback('Du har ikke nok penger til å bruke privat kjørebane', 'error');
    }

    if (isset($_GET['train'])) {
        if (!rc_ready($_SESSION['ID'], $pdo)) {

            $type = $_GET['train'];

            if (in_array($type, $legal)) {
                if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < $private_track) {
                    header("Location: ?side=rc&notenoughmoney");
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], 'privat trening start', $pdo);
                    $amount = $pr_training;
                    train_rc($_SESSION['ID'], $type, $amount, $pdo);
                    take_money($_SESSION['ID'], $private_track, $pdo);
                    rc_give_cooldown($_SESSION['ID'], $waittime, $pdo);

                    if (race_club_existence(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                        $owner = race_club_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
                        $price = $private_track * 0.85;

                        $sql = "UPDATE rc_owner SET RCOWN_bank = (RCOWN_bank + ?) WHERE RCOWN_acc_id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$price, $owner]);
                    }

                    update_bedrift_inntekt($_SESSION['ID'], 3, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $private_track, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], 'privat trening ferdig', $pdo);
                    header("Location: ?side=rc&trained=" . $type . "");
                }
            } else {
                echo feedback('Ugyldig verdi', 'error');
            }
        } else {
            echo feedback('Du har ventetid', 'fail');
        }
    }

    if (isset($_POST['race'])) {
        if (!race_ready($_SESSION['ID'], $pdo)) {
            $my_hk = ((race_club_drift($_SESSION['ID'], $pdo) + race_club_drag($_SESSION['ID'], $pdo) + race_club_race($_SESSION['ID'], $pdo)) / 3);

            $contestant_id = get_random_rc_acc_id_contestant($_SESSION['ID'], $pdo);
            $contestant_hk = $contestant_id && get_random_hk_contestant($contestant_id, $pdo);

            if ($contestant_id == null) {
                echo feedback("Ingen kjører funnet.", "fail");
            } else {
                user_log($_SESSION['ID'], $_GET['side'], 'race start', $pdo);
                race_give_cooldown($_SESSION['ID'], $waittime_race, $pdo);
                if ($contestant_hk && $contestant_hk > $my_hk) {
                    insert_to_last_race($_SESSION['ID'], $contestant_id, $contestant_id, $pdo);
                    update_race($_SESSION['ID'], 0, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], 'race tap', $pdo);
                    header("Location: ?side=rc&loose=" . $contestant_id . "");
                } else {
                    update_konk($_GET['side'], 1, $_SESSION['ID'], $pdo);

                    if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 46) {
                        mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                    }

                    if (mt_rand(0, 20) == 20) {
                        update_things($_SESSION['ID'], 31, $pdo);
                        send_notification($_SESSION['ID'], "Du fant gresskar når du utførte en kriminell handling!", $pdo);
                    }

                    insert_to_last_race($_SESSION['ID'], $contestant_id, $_SESSION['ID'], $pdo);
                    give_exp($_SESSION['ID'], $exp, $pdo);
                    update_race($_SESSION['ID'], 1, $pdo);
                    $race_win_money = mt_rand(150, 350);

                    give_money($_SESSION['ID'], $race_win_money, $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], 'race vinn', $pdo);
                    header("Location: ?side=rc&win=" . $contestant_id . "&money=" . $race_win_money);
                }
            }
        } else {
            echo feedback("Du har ventetid på race", "error");
        }
    }

    if (isset($_GET['win'])) {
        $race_win_money = $_GET['money'];
        echo feedback("Du vant over " . ACC_username($_GET['win'], $pdo) . " i race og vinner " . number($race_win_money) . " kr!", 'success');
    }

    if (isset($_GET['loose'])) {
        echo feedback("Du tapte over " . ACC_username($_GET['loose'], $pdo) . " i race!", 'fail');
    }

    $price_private = 50000000;

    if (isset($_POST['build'])) {
        if (max_eiendeler($_SESSION['ID'], $pdo)) {
            echo feedback("Du har allerede 2 forskjellige eiendeler", "fail");
        } elseif (race_club_existence(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
            echo feedback("Det er allerede en eier i denne byen.", "fail");
        } elseif (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < $price_private) {
            echo feedback("Du har ikke nok penger for å bygge en privat kjørebane.", "fail");
        } elseif (race_club_owner_already($_SESSION['ID'], $pdo)) {
            echo feedback("Du eier allerede en kjørebane i en annen by.", "fail");
        } else {
            $sql = "INSERT INTO rc_owner (RCOWN_acc_id, RCOWN_city) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

            take_money($_SESSION['ID'], $price_private, $pdo);

            echo feedback("Du er nå eier av kjørebane i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)), "success");
        }
    }

    if (firma_on_marked(3, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>
        <div class="col-7 single" style="padding-bottom: 5px;">
            <div class="content">
                <p class="description">Kjørebanen i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) ?> ligger ute på <a href="?side=marked&id=5">marked</a></p>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="col-7 single privat_kjorebane" style="display: none; padding-bottom: 5px;">
        <div class="content">
            <h4>Bygg privat kjørebane</h4>
            <p class="description">Ved å bygge en kjørebane vil du få 10 000kr hver gang noen trener bilen sin.
                Prisen for å bygge en privat kjørebane er <?php echo number($price_private); ?> kr</p>
            <form method="post">
                <input type="submit" name="build" value="Bygg kjørebane">
            </form>
        </div>
    </div>
    <div class="col-7" style="margin-top: -10px;">
        <div class="content">
            <h4>Streetrace</h4>
            <p class="description">
                Det koster 10 000kr å trene, pengene går til eieren av kjørebanen.
                Ventetid på kjøretrening er <?php echo $waittime - time(); ?> sekunder og du kan trene så mye du vil.
                Du får 1 ferdighetspoeng per trening.
            </p>

            <span class="small_header">Min bil</span>
            <br>
            <div class="col-12">
                <h3>
                    <?php
                    $total_hk_ = (race_club_drift($_SESSION['ID'], $pdo) + race_club_drag($_SESSION['ID'], $pdo) + race_club_race($_SESSION['ID'], $pdo));
                    $total_hk = $total_hk_ / 3;

                    echo number($total_hk);
                    ?>
                    HK</h3>
            </div>

            <div class="col-4">
                Drifting: <h3 style="display: inline; padding-bottom:10px;"><?php echo number(race_club_drift($_SESSION['ID'], $pdo)); ?></h3>
                <br><br>
                <a href="?side=rc&train=drift" class="image_rc"><img src="img/action/actions/streetrace_0.png"></a>
            </div>
            <div class="col-4">
                Dragrace: <h3 style="display: inline; padding-bottom:10px;"><?php echo number(race_club_drag($_SESSION['ID'], $pdo)); ?></h3>
                <br><br>
                <a href="?side=rc&train=drag" class="image_rc"><img src="img/action/actions/streetrace_1.png"></a>
            </div>
            <div class="col-4">
                Kappløp: <h3 style="display: inline; padding-bottom:10px;"><?php echo number(race_club_race($_SESSION['ID'], $pdo)); ?></h3>
                <br><br>
                <a href="?side=rc&train=race" class="image_rc"><img src="img/action/actions/streetrace_2.png"></a>
            </div>
            <div style="clear: both;"></div>

            <style>
                .image_rc img {
                    filter: opacity(30%);
                    transition: .2s;
                }

                .image_rc:hover img {
                    cursor: pointer;
                    filter: opacity(100%);
                }
            </style>

            <?php if (race_club_existence(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>
                <span class="description">Kjørebanen i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> er eid av <?php echo ACC_username(race_club_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo), $pdo); ?></span>
                <br><br>
            <?php } else {
                if (firma_on_marked(3, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                    echo '<p class="description">Den private kjørebanen i ' . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . '. ligger ute på <a href="?side=marked&id=5">marked</a></p>';
                } else {
                    echo feedback('Ingen eier kjørebanen i ' . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . '. Du kan kjøpe den her: <span class="show_kjop">Kjøp</span>', 'blue');
                }
            }

            $stmt = $pdo->prepare('SELECT CD_rc FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $time_left = $row['CD_rc'] - time();

            $minutes = floor($time_left / 60);
            $seconds_training = $time_left - $minutes * 60;

            ?>

            <div id="training_cooldown">
                <?php

                if (rc_ready($_SESSION['ID'], $pdo)) {

                ?>
                    <div id="cooldownContainer" class="feedback blue">
                        <i class="fi fi-rr-alarm-clock"></i>
                        <span id="cooldownText">
                            Du har nylig trent på kjøring. Du må vente <?php echo $seconds_training ?> sekunder før du kan trene igjen
                        </span>
                    </div>
                    <script>
                        var timeleft_training = <?php echo $seconds_training ?>;
                        var downloadTimer = setInterval(function() {
                            timeleft_training--;
                            document.querySelectorAll(`#training_cooldown`).forEach(e => {
                                document.getElementById('cooldownText').textContent = 'Du har nylig trent på kjøring. Du må vente ' + timeleft_training + ' sekunder før du kan trene igjen';
                            });
                            if (timeleft_training <= 0) {
                                document.querySelectorAll(`#training_cooldown`).forEach(e => {
                                    document.getElementById("cooldownContainer").style.display = "none";
                                });
                                clearInterval(downloadTimer);
                            }
                        }, 1000);
                    </script>
                <?php
                }

                ?>

            </div>


            <?php
            if (race_club_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {

                $amount_sell = 10000000;

                if (isset($_POST['sell'])) {
                    if (race_club_owner(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {
                        $sql = "DELETE FROM rc_owner WHERE RCOWN_acc_id = " . $_SESSION['ID'] . "";
                        $pdo->exec($sql);

                        give_money($_SESSION['ID'], $amount_sell, $pdo);

                        header("Location:?side=rc&sold");
                    } else {
                        echo feedback("Ugyldig handling", "error");
                    }
                }

                if (isset($_POST['ta_ut_bank'])) {
                    $money = race_club_bank(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);

                    if ($money && $money > 0 && is_numeric($money)) {
                        give_money($_SESSION['ID'], $money, $pdo);

                        $sql = "UPDATE rc_owner SET RCOWN_bank = ? WHERE RCOWN_acc_id = ?";
                        $pdo->prepare($sql)->execute([0, $_SESSION['ID']]);

                        echo feedback("Du tok ut " . number($money) . "kr", 'success');
                    } else {
                        echo feedback('Ugyldig beløp', 'error');
                    }
                }

            ?>
                <div class="pad_10">
                    <p class="description">Som eier av privat kjørebane er det du som får pengene fra treninger. 15% av beløpet går til vedlikehold og du kan beholde resten.</p>
                    <p>Disponibelt: <?php echo number(race_club_bank(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?> kr</p>
                    <form method="post">
                        <input type="submit" name="ta_ut_bank" value="Ta ut penger">
                        <input type="submit" name="sell" onclick="return confirm('Er du sikker på at du ønsker å selge den private kjørebanen for <?php echo number($amount_sell); ?>?')" value="Selg til staten">
                    </form>
                </div>
            <?php } ?>

        </div>
    </div>
    <div class="col-5" style="margin-top: -10px">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <p class="description">Ved å kjøre kappløp bruker du dine ferdighetspoeng til å vinne over andre sjåfører.</p>
            <?php

            $stmt_race = $pdo->prepare('SELECT CD_race FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt_race->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row_race = $stmt_race->fetch(PDO::FETCH_ASSOC);

            $time_left_race = $row_race['CD_race'] - time();

            $minutes = floor($time_left_race / 60);
            $seconds_racing = $time_left_race - $minutes * 60;

            ?>
            <form method="post">
                <div id="racing_cooldown">
                    <?php

                    if (race_ready($_SESSION['ID'], $pdo)) {

                    ?>
                        <div id="cooldownContainerRace" class="feedback blue">
                            <i class="fi fi-rr-alarm-clock"></i>
                            <span id="cooldownTextRacing">
                                Du har nylig kjørt kappløp. Du må vente <?php echo $seconds_racing ?> sekunder før du kan kjøre kappløp igjen
                            </span>
                        </div>
                        <script>
                            var timeleft_racing = <?php echo $seconds_racing ?>;
                            var downloadTimer_racing = setInterval(function() {
                                timeleft_racing--;
                                document.querySelectorAll(`#racing_cooldown`).forEach(e => {
                                    document.getElementById('cooldownTextRacing').textContent =
                                        'Du har nylig kjørt kappløp. Du må vente ' + timeleft_racing + ' sekunder før du kan kjøre kappløp igjen';
                                });
                                if (timeleft_racing <= 0) {
                                    document.querySelectorAll(`#racing_cooldown`).forEach(e => {
                                        document.getElementById("cooldownContainerRace").style.display = "none";
                                        e.innerHTML = '<input style="width: 100%;" type="submit" name="race" value="Kjør kappløp">';
                                    });
                                    clearInterval(downloadTimer_racing);
                                }
                            }, 1000);
                        </script>
                    <?php
                    } else {
                        echo '<input style="width: 100%;" type="submit" name="race" value="Kjør kappløp">';
                    }

                    ?>
                </div>
            </form>
            <h4 style="margin: 12px 0px">Siste 5 race</h4>
            <?php

            $sql = "SELECT LR_winner, LR_contestant, LR_date FROM last_race WHERE LR_acc_id = " . $_SESSION['ID'] . " ORDER BY LR_date DESC LIMIT 5";
            $stmt = $pdo->query($sql);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                if ($row['LR_winner'] == $_SESSION['ID']) {
                    echo feedback("Vinn mot " . ACC_username($row['LR_contestant'], $pdo) . " - " . date_to_text($row['LR_date']), 'success');
                } else {
                    echo feedback("Tap mot " . ACC_username($row['LR_contestant'], $pdo) . " - " . date_to_text($row['LR_date']), 'fail');
                }
            }

            ?>
        </div>
    </div>
<?php } ?>

<style>
    .show_kjop:hover {
        cursor: pointer;
        text-decoration: underline;
    }
</style>

<script>
    number_space("#number");

    $(document).ready(function() {
        $(".show_kjop").click(function() {
            $(".privat_kjorebane").toggle();
        });
    });
</script>