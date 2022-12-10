<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    function insert_last_stjel($id, $user, $pdo)
    {
        $sql = "INSERT INTO last_stjel (LASTJ_acc_id, LASTJ_username, LASTJ_date) VALUES (?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id, $user, time()]);
    }

    function get_last_stjel($id, $pdo)
    {
        $stmt = $pdo->prepare("SELECT * FROM last_stjel WHERE LASTJ_acc_id = ? ORDER BY LASTJ_date desc");
        $stmt->execute([$id]);
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            return false;
        } else {
            $query = $pdo->prepare("SELECT LASTJ_username FROM last_stjel WHERE LASTJ_acc_id = ? ORDER BY LASTJ_date desc");
            $query->execute(array($id));
            $LASTJ_row = $query->fetch(PDO::FETCH_ASSOC);

            return $LASTJ_row['LASTJ_username'] ?? NULL;
        }
    }

    $exp = 2;
    $percentage = 0;
    $min_money_steal = 1500;
    $max_money_steal = INF;
    $chance = mt_rand(1, 100);
    $cooldown = time() + 240; // 4 minutter = 240 sekunder
    $rand = mt_rand(1, 99);
    $min_steal = 1000;

    if (isset($_POST['steal_random'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'alternativ: random', $pdo);
        if (steal_ready($_SESSION['ID'], $pdo)) {
            user_log($_SESSION['ID'], $_GET['side'], 'ventetid', $pdo);
            echo feedback("Du har ventetid.", "blue");
        } elseif (!isset($_POST['radio'])) {
            user_log($_SESSION['ID'], $_GET['side'], 'ugyldig alternativ', $pdo);
            echo feedback("Du må velge ett gyldig alternativ", "fail");
        } else {

            if ($_POST['radio'] == "money") {
                if (get_acc_id_without_protection($_SESSION['ID'], $pdo) == null) {
                    user_log($_SESSION['ID'], $_GET['side'], 'Ingen spillere uten beskyttelse funnet', $pdo);
                    echo feedback("Ingen spillere uten beskyttelse ble funnet.", "fail");
                } else {
                    $id_steal = get_acc_id_without_protection($_SESSION['ID'], $pdo);

                    $username_steal = ACC_session_row($id_steal, 'ACC_username', $pdo);

                    update_dagens_utfordring($_SESSION['ID'], 3, $pdo);

                    steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                    $steal_money = AS_session_row($id_steal, 'AS_money', $pdo);

                    if ($steal_money < $min_steal) {
                        user_log($_SESSION['ID'], $_GET['side'], 'Feilet å stjele penger fra ' . $username_steal . '', $pdo);
                        echo feedback("Du feilet å stjele penger fra " . $username_steal . "", "error");

                        steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                    } else {
                        if ($id_steal == get_bot_id('Joe Bananas', $pdo)) {
                            $money_amount = mt_rand(10000, 20000);
                            give_money($_SESSION['ID'], $money_amount, $pdo);
                        } else {
                            $money_amount = mt_rand($min_steal, $steal_money);
                            give_money($_SESSION['ID'], $money_amount, $pdo);
                            take_money($id_steal, $money_amount, $pdo);
                        }

                        if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 22) {
                            mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + $money_amount, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                        }

                        $msg = "Du har blitt frastjålet " . number($money_amount) . " kr av " . username_plain($_SESSION['ID'], $pdo) . ". Kjøp ransbeskyttelse for å unngå å bli frastjålet penger.";
                        if (notificationSettings('stolenFrom', $id_steal, $pdo)) {
                            send_notification($id_steal, $msg, $pdo);
                        }
                        give_exp($_SESSION['ID'], $exp, $pdo);
                        check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                        user_log($_SESSION['ID'], $_GET['side'], 'Stjeler ' . number($money_amount) . ' fra ' . $username_steal . '', $pdo);
                        echo feedback("Du klarte å stjele " . number($money_amount) . " kr fra " . $username_steal, "success");
                    }
                }
            } elseif ($_POST['radio'] == "car") {
                $stmt = $pdo->prepare("SELECT GA_id, GA_car_id FROM garage WHERE GA_acc_id = :id ORDER BY RAND() LIMIT 1");
                $stmt->execute(['id' => $id_steal]);
                $car_stolen = $stmt->fetch();

                if ($full_garage) {
                    echo feedback("Du har kun plass til $max_garage biler i din garasje. <a href='?side=poeng'>Du kan gjøre noe med det her</a>", "blue");
                } else {
                    $id_steal = get_random_garage_id($_SESSION['ID'], $pdo);
                    steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                    if (stjel_sjanse_beskyttelse($id_steal, 'garasje', $pdo) > $rand) {
                        user_log($_SESSION['ID'], $_GET['side'], 'Feilet å stjele bil fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                        echo feedback("Du feilet å stjele bil fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "error");

                        steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                        update_stjel($_SESSION['ID'], 0, $pdo);
                    } elseif (!$car_stolen) {
                        user_log($_SESSION['ID'], $_GET['side'], 'Garasjen er tom', $pdo);
                        echo feedback("Garasjen til " . ACC_session_row($id_steal, 'ACC_username', $pdo), "error");

                        steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                        update_stjel($_SESSION['ID'], 0, $pdo);
                    } else {
                        $sql = "UPDATE garage SET GA_acc_id = '" . $_SESSION['ID'] . "' WHERE GA_id='" . $car_stolen['GA_id'] . "'";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        $msg = "Du har blitt frastjålet en " . car($car_stolen['GA_car_id']) . " fra " . username_plain($_SESSION['ID'], $pdo) . ". Kjøp beskyttelse til garasjen for å unngå å bli frastjålet biler.";
                        if (notificationSettings('stolenFrom', $id_steal, $pdo)) {
                            send_notification($id_steal, $msg, $pdo);
                        }

                        give_exp($_SESSION['ID'], $exp, $pdo);
                        check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
                        update_dagens_utfordring($_SESSION['ID'], 3, $pdo);

                        user_log($_SESSION['ID'], $_GET['side'], 'Stjeler ' . car($car_stolen['GA_car_id']) . ' fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                        echo feedback("Du klarte å stjele en " . car($car_stolen['GA_car_id']) . " fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "success");
                    }
                }
            } elseif ($_POST['radio'] == "thing") {
                if ($full_storage) {
                    user_log($_SESSION['ID'], $_GET['side'], 'Fullt lager', $pdo);
                    echo feedback("Du har kun plass til $max_storage ting i ditt lager. <a href='?side=poeng'>Du kan gjøre noe med det her</a>", "blue");
                } else {
                    steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                    $id_steal = get_random_garage_id($_SESSION['ID'], $pdo);
                    steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                    if (stjel_sjanse_beskyttelse($id_steal, 'ting', $pdo) > $rand) {
                        user_log($_SESSION['ID'], $_GET['side'], 'Feilet å stjele ting fra ', $pdo);
                        echo feedback("Du feilet å stjele ting fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "error");

                        steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                        update_stjel($_SESSION['ID'], 0, $pdo);
                    } elseif (get_random_thing_id($id_steal, $pdo) == null) {
                        user_log($_SESSION['ID'], $_GET['side'], 'Lageret til ' . $username_steal . ' er tomt', $pdo);
                        echo feedback("Lageret til " . ACC_session_row($id_steal, 'ACC_username', $pdo) . " er tom", "error");

                        steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                        update_stjel($_SESSION['ID'], 0, $pdo);
                    } else {
                        $lager_id_steal = get_random_thing_id($id_steal, $pdo);
                        $thing_id_steal = get_thing_id($lager_id_steal, $pdo);

                        update_things($_SESSION['ID'], $thing_id_steal, $pdo);
                        remove_thing($lager_id_steal, $pdo);

                        update_stjel($_SESSION['ID'], 1, $pdo);

                        $msg = "Du har blitt frastjålet en " . thing($thing_id_steal) . " fra " . username_plain($_SESSION['ID'], $pdo) . ". Kjøp beskyttelse til lageret for å unngå å bli frastjålet ting.";
                        if (notificationSettings('stolenFrom', $id_steal, $pdo)) {
                            send_notification($id_steal, $msg, $pdo);
                        }
                        give_exp($_SESSION['ID'], $exp, $pdo);

                        check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
                        update_dagens_utfordring($_SESSION['ID'], 3, $pdo);

                        user_log($_SESSION['ID'], $_GET['side'], 'Stjeler ' . thing($thing_id_steal) . ' fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                        echo feedback("Du klarte å stjele " . thing($thing_id_steal) . " fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "success");
                    }
                }
            }
        }
    }


    if (isset($_POST['post'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Alternativ: ikke random', $pdo);
        if (steal_ready($_SESSION['ID'], $pdo)) {
            user_log($_SESSION['ID'], $_GET['side'], 'Ventetid', $pdo);
            echo feedback("Du har ventetid.", "blue");
        } elseif (!isset($_POST['radio'])) {
            user_log($_SESSION['ID'], $_GET['side'], 'Ugyldig alternativ', $pdo);
            echo feedback("Du må velge ett gyldig alternativ", "fail");
        } else {
            if (user_exist($_POST['specific_player'], $pdo)) {
                $username = $_POST['specific_player'];

                $sthandler = $pdo->prepare("SELECT * FROM accounts WHERE ACC_username = :name");
                $sthandler->bindParam(':name', $username);
                $sthandler->execute();

                $ACC_steal_query = $pdo->prepare("SELECT * FROM accounts WHERE ACC_username = ?");
                $ACC_steal_query->execute(array($username));
                $ACC_steal_row = $ACC_steal_query->fetch(PDO::FETCH_ASSOC);

                $AS_steal_query = $pdo->prepare("SELECT * FROM accounts_stat WHERE AS_id = ?");
                $AS_steal_query->execute(array($ACC_steal_row['ACC_id']));
                $AS_steal_row = $AS_steal_query->fetch(PDO::FETCH_ASSOC);

                if ($ACC_steal_row['ACC_id'] == $_SESSION['ID']) {
                    user_log($_SESSION['ID'], $_GET['side'], 'Feiler å rane seg selv', $pdo);
                    echo feedback("Du kan ikke rane deg selv.", "fail");
                } else {
                    insert_last_stjel($_SESSION['ID'], $ACC_steal_row['ACC_username'], $pdo);

                    $id_steal = $ACC_steal_row['ACC_id'];


                    if ($_POST['radio'] == "money") {
                        if ($AS_steal_row['AS_protection'] > 0) {
                            user_log($_SESSION['ID'], $_GET['side'], 'Brukeren har beskyttelse', $pdo);
                            echo feedback("Brukeren har beskyttelse", "error");
                        } else {
                            $username_steal = ACC_session_row($id_steal, 'ACC_username', $pdo);

                            update_dagens_utfordring($_SESSION['ID'], 3, $pdo);

                            steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                            $steal_money = AS_session_row($id_steal, 'AS_money', $pdo);

                            if ($steal_money < $min_steal) {
                                user_log($_SESSION['ID'], $_GET['side'], 'Feilet å stjele penger fra ' . $username_steal . '', $pdo);
                                echo feedback("Du feilet å stjele penger fra " . $username_steal . "", "error");

                                steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                            } else {
                                if ($id_steal == get_bot_id('Joe Bananas', $pdo)) {
                                    $money_amount = mt_rand(10000, 20000);
                                    give_money($_SESSION['ID'], $money_amount, $pdo);
                                } else {
                                    $money_amount = mt_rand($min_steal, $steal_money);
                                    give_money($_SESSION['ID'], $money_amount, $pdo);
                                    take_money($id_steal, $money_amount, $pdo);
                                }

                                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 22) {
                                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + $money_amount, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                                }

                                $msg = "Du har blitt frastjålet " . number($money_amount) . " kr av " . username_plain($_SESSION['ID'], $pdo) . ". Kjøp ransbeskyttelse for å unngå å bli frastjålet penger.";
                                if (notificationSettings('stolenFrom', $id_steal, $pdo)) {
                                    send_notification($id_steal, $msg, $pdo);
                                }
                                give_exp($_SESSION['ID'], $exp, $pdo);
                                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                                user_log($_SESSION['ID'], $_GET['side'], 'Stjeler ' . number($money_amount) . ' fra ' . $username_steal . '', $pdo);
                                echo feedback("Du klarte å stjele " . number($money_amount) . " kr fra " . $username_steal . "", "success");
                            }
                        }
                    } elseif ($_POST['radio'] == "car") {
                        $stmt = $pdo->prepare("SELECT GA_id, GA_car_id FROM garage WHERE GA_acc_id = :id ORDER BY RAND() LIMIT 1");
                        $stmt->execute(['id' => $id_steal]);
                        $car_stolen = $stmt->fetch();

                        if ($full_garage) {
                            echo feedback("Du har kun plass til $max_garage biler i din garasje. <a href='?side=poeng'>Du kan gjøre noe med det her</a>", "blue");
                        } else {
                            steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                            if (stjel_sjanse_beskyttelse($id_steal, 'garasje', $pdo) > $rand) {
                                user_log($_SESSION['ID'], $_GET['side'], 'Feiler å stjele bil fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                                echo feedback("Du feilet å stjele bil fra " . ACC_session_row($id_steal, 'ACC_username', $pdo) . "", "error");

                                steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                update_stjel($_SESSION['ID'], 0, $pdo);
                            } elseif (!$car_stolen) {
                                user_log($_SESSION['ID'], $_GET['side'], 'Garasjen til ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . ' er tom', $pdo);
                                echo feedback("Garasjen til " . ACC_session_row($id_steal, 'ACC_username', $pdo) . " er tom", "error");

                                steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                update_stjel($_SESSION['ID'], 0, $pdo);
                            } else {
                                $sql = "UPDATE garage SET GA_acc_id = '" . $_SESSION['ID'] . "' WHERE GA_id='" . $car_stolen['GA_id'] . "'";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                $msg = "Du har blitt frastjålet en " . car($car_stolen['GA_car_id']) . " fra " . username_plain($_SESSION['ID'], $pdo) . ". Kjøp beskyttelse til garasjen for å unngå å bli frastjålet biler.";
                                if (notificationSettings('stolenFrom', $id_steal, $pdo)) {
                                    send_notification($id_steal, $msg, $pdo);
                                }
                                give_exp($_SESSION['ID'], $exp, $pdo);
                                update_dagens_utfordring($_SESSION['ID'], 3, $pdo);

                                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
                                user_log($_SESSION['ID'], $_GET['side'], 'stjeler en ' . car($car_stolen['GA_car_id']) . ' fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                                echo feedback("Du klarte å stjele en " . car($car_stolen['GA_car_id']) . " fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "success");
                            }
                        }
                    } elseif ($_POST['radio'] == "thing") {
                        if ($full_storage) {
                            echo feedback("Du har kun plass til $max_storage ting i ditt lager. <a href='?side=poeng'>Du kan gjøre noe med det her</a>", "blue");
                        } else {
                            steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                            steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                            if (stjel_sjanse_beskyttelse($id_steal, 'ting', $pdo) > $rand) {
                                user_log($_SESSION['ID'], $_GET['side'], 'Feilet å stjele ting fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                                echo feedback("Du feilet å stjele ting fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "error");

                                steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                update_stjel($_SESSION['ID'], 0, $pdo);
                            } elseif (get_random_thing_id($id_steal, $pdo) == null) {
                                user_log($_SESSION['ID'], $_GET['side'], 'Lageret til ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . ' er tomt', $pdo);
                                echo feedback("Lageret til " . ACC_session_row($id_steal, 'ACC_username', $pdo) . " er tomt", "error");

                                steal_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                update_stjel($_SESSION['ID'], 0, $pdo);
                            } else {
                                $lager_id_steal = get_random_thing_id($id_steal, $pdo);
                                $thing_id_steal = get_thing_id($lager_id_steal, $pdo);

                                update_things($_SESSION['ID'], $thing_id_steal, $pdo);
                                remove_thing($lager_id_steal, $pdo);

                                update_stjel($_SESSION['ID'], 1, $pdo);

                                $msg = "Du har blitt frastjålet en " . thing($thing_id_steal) . " fra " . username_plain($_SESSION['ID'], $pdo) . ". Kjøp beskyttelse til lageret for å unngå å bli frastjålet ting.";
                                if (notificationSettings('stolenFrom', $id_steal, $pdo)) {
                                    send_notification($id_steal, $msg, $pdo);
                                }
                                give_exp($_SESSION['ID'], $exp, $pdo);

                                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
                                update_dagens_utfordring($_SESSION['ID'], 3, $pdo);

                                user_log($_SESSION['ID'], $_GET['side'], 'stjeler ' . thing($thing_id_steal) . ' fra ' . ACC_session_row($id_steal, 'ACC_username', $pdo) . '', $pdo);
                                echo feedback("Du klarte å stjele " . thing($thing_id_steal) . " fra " . ACC_session_row($id_steal, 'ACC_username', $pdo), "success");
                            }
                        }
                    }
                }
            }
        }
    }

?>
    <div class="col-7 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <?php

            if (steal_ready($_SESSION['ID'], $pdo)) {

                $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                $stmt->execute(array(
                    ':cd_id' => $_SESSION['ID']
                ));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $time_left = $row['CD_steal'] - time();

                $minutes = floor($time_left / 60);
                $seconds = $time_left - $minutes * 60;

                echo feedback('Du har nylig utført et tyveriforsøk mot en spiller. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan stjele igjen.', 'cooldown'); ?>
                <script>
                    timeleft(<?php echo $time_left; ?>, "countdowntimer");
                </script>
            <?php } else { ?>

                <p>
                    <center>Hva ønsker du å stjele?</center>
                </p>
                <div class="col-6 single">
                    <form method="post">
                        <table>
                            <tr>
                                <td>
                                    <label class="label_container" style="margin-top: 10px;">Stjel penger fra spiller
                                        <input type="radio" name="radio" value="money">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="label_container" style="margin-top: 10px;" <?php if ($full_garage) : ?> title="Garasjen din er full!" <?php endif; ?>>Stjel tifeldig bil fra spiller
                                        <input type="radio" name="radio" value="car" <?php if ($full_garage) : ?> disabled <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="label_container" style="margin-top: 10px;" <?php if ($full_storage) : ?> title="Lageret ditt er full!" <?php endif; ?>>Stjel tifeldig ting fra spiller
                                        <input type="radio" name="radio" value="thing" <?php if ($full_storage) : ?> disabled <?php endif; ?>>
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                        </table>
                        <p class="description">Stjel fra spesifikk person</p>
                        <input type="text" style="width: 50%; float: left;" name="specific_player" value="<?php echo get_last_stjel($_SESSION['ID'], $pdo); ?>" placeholder="Brukernavn">
                        <input type="submit" style="width: 49%; float: right;" name="post" value="Stjel">

                        <p class="description" style="margin-top: 70px; margin-bottom: 0px;">Stjel fra tilfeldig mafioso</p>
                        <input style="margin-top: 10px; width: 100%;" type="submit" name="steal_random" value="Stjel fra tilfeldig">
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>