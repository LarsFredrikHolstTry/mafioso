<?php

include_once 'functions/oppdrag.php';

$vito = "Vito";

$cooldown = 1800 + time();

if (active_drapsfri($pdo)) {
    echo feedback("Det er ikke mulig å drepe under drapsfri", "blue");
}

$weapon_add[0] = 1;
$weapon_add[1] = 2;
$weapon_add[2] = 4;
$weapon_add[3] = 7;
$weapon_add[4] = 9;
$weapon_add[5] = 11;
$weapon_add[6] = 13;
$weapon_add[7] = 16;
$weapon_add[8] = 20;
$weapon_add[9] = 24;
$weapon_add[10] = 27;
$weapon_add[11] = 29;
$weapon_add[12] = 31;
$weapon_add[13] = 33;
$weapon_add[14] = 35;
$weapon_add[15] = 36;
$weapon_add[16] = 37;
$weapon_add[17] = 50;
$weapon_add[18] = 75;
$weapon_add[19] = 100;

if (isset($_POST['kill'])) {
    if (kill_ready($_SESSION['ID'], $pdo)) {
        echo feedback("Du har ventetid!", "error");
    } else {
        if (isset($_POST['username']) && isset($_POST['bullet_amount'])) {
            $_POST['bullet_amount'] = remove_space($_POST['bullet_amount']);
            if (is_numeric($_POST['bullet_amount']) && $_POST['bullet_amount'] <= AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo) && $_POST['bullet_amount'] > 0) {
                if (strcasecmp($vito, $_POST['username']) == 0) {
                    if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 19) {
                        if (AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo) >= 50 && $_POST['bullet_amount'] >= 50) {
                            if (AS_session_row($_SESSION['ID'], 'AS_city', $pdo) == 1) {
                                check_mission_done(1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), 1, $_SESSION['ID'], $pdo);

                                take_bullets($_SESSION['ID'], $_POST['bullet_amount'], $pdo);
                                kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                            } else {
                                $cooldown = $cooldown / 4;
                                kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                take_bullets($_SESSION['ID'], $_POST['bullet_amount'], $pdo);

                                echo feedback("Du feilet på Vito. Som du har hørt fra din detektiv skal han være i Las Vegas.", "fail");
                            }
                        } else {
                            $cooldown = $cooldown / 4;
                            kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                            take_bullets($_SESSION['ID'], $_POST['bullet_amount'], $pdo);

                            echo feedback('Du feilet på drapet mot Vito!', "error");
                        }
                    } elseif (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) > 19) {
                        echo feedback('Vito er allerede drept', "blue");
                    } else {
                        echo feedback("Du er ikke på oppdrag 19", "blue");
                    }
                } else {


                    if (active_drapsfri($pdo)) {
                        echo feedback("Det er ikke mulig å drepe under drapsfri!", "error");
                    } else {

                        $username = $_POST['username'];
                        if (user_exist($username, $pdo)) {
                            $acc_id = get_acc_id($username, $pdo);

                            if ($acc_id == 29) {
                                echo feedback("Utpressings-botter kan ikke drepes", "error");
                            } else {
                                $query = $pdo->prepare("SELECT * FROM accounts WHERE ACC_id = ?");
                                $query->execute(array($acc_id));
                                $KILL_ACC_row = $query->fetch(PDO::FETCH_ASSOC);

                                $query = $pdo->prepare("SELECT * FROM accounts_stat WHERE AS_id = ?");
                                $query->execute(array($acc_id));
                                $KILL_AS_row = $query->fetch(PDO::FETCH_ASSOC);

                                $acc_type = $KILL_ACC_row['ACC_type'];

                                $seven_days = 604800;
                                $my_start_time = false;
                                $victim_start_time = false;

                                if ((ACC_session_row($_SESSION['ID'], 'ACC_register_date', $pdo) + $seven_days) > time()) {
                                    $my_start_time = true;
                                }

                                if (($KILL_ACC_row['ACC_register_date']  + $seven_days) > time()) {
                                    $victim_start_time = true;
                                }

                                if ($my_start_time) {
                                    echo feedback('Du har startbeskyttelse', "fail");
                                } elseif ($victim_start_time) {
                                    echo feedback('Ditt offer har startbeskyttelse', "fail");
                                } elseif ($acc_type == 4) {
                                    echo feedback("Brukeren er allerede død", "fail");
                                } elseif ($acc_id == $_SESSION['ID']) {
                                    echo feedback("Du kan ikke drepe deg selv", "fail");
                                } elseif ($acc_type == 0) {
                                    if (!player_in_bunker($KILL_AS_row['AS_id'], $pdo) && $KILL_AS_row['AS_city'] == AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) {

                                        $bullets = $_POST['bullet_amount'];

                                        $exp = $KILL_AS_row['AS_exp'] ? $KILL_AS_row['AS_exp'] : 0;

                                        if (family_member_exist($KILL_AS_row['AS_id'], $pdo)) {

                                            $my_family_id = get_my_familyID($KILL_AS_row['AS_id'], $pdo);

                                            $addon = get_forsvar_from_family(get_family_defence($my_family_id, $pdo), get_my_familyrole($KILL_AS_row['AS_id'], $pdo), total_family_members($my_family_id, $pdo));
                                        } else {
                                            $addon = 0;
                                        }

                                        if (half_fp($acc_id, $pdo)) {
                                            $def = $KILL_AS_row['AS_def'] / 2 + $addon;
                                        } else {
                                            $def = $KILL_AS_row['AS_def'] + $addon;
                                        }

                                        $weapon = AS_session_row($_SESSION['ID'], 'AS_weapon', $pdo);
                                        $health = $KILL_AS_row['AS_health'];

                                        // Penger på hånden til den man dreper
                                        $money_on_hand = round($KILL_AS_row['AS_money']);

                                        $exp_kill = $KILL_AS_row['AS_exp'] ? $KILL_AS_row['AS_exp'] : 0;

                                        // 10% av exp til den man dreper
                                        $ten_percent_of_exp = round($exp_kill * 0.10);

                                        // Total forsvars-poeng (exp + forsvar)
                                        $exp = $exp * 100;
                                        $total_points = $exp + $def;

                                        // Antall kuler som trengs for å drepe
                                        $kulekrav = ($total_points / points_taken_pr_bullet($weapon)) * 3.55;
                                        $kulekrav = ($kulekrav / 100);

                                        // Hvor mye helse som skal fjernes
                                        $take_health = $bullets / $kulekrav * $weapon_add[$weapon];

                                        // Hvor mye FP som skal fjernes
                                        $amount_fp_remove = ($take_health * $total_points) / 100;
                                        $amount_fp_remove = round($amount_fp_remove);

                                        if ($bullets >= $kulekrav) {

                                            give_exp($_SESSION['ID'], $ten_percent_of_exp, $pdo);
                                            give_half_hp($_SESSION['ID'], $pdo);
                                            check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                                            if ($money_on_hand > 0) {
                                                give_money($_SESSION['ID'], $money_on_hand, $pdo);
                                            }

                                            if ($KILL_AS_row['AS_rank'] > 4) {
                                                update_drap($_SESSION['ID'], 1, $pdo);
                                            }

                                            if (AS_session_row($_SESSION['ID'], 'AS_lyddemper', $pdo) == 0) {
                                                $text = " ble drept av " . ACC_username($_SESSION['ID'], $pdo) . "";

                                                last_event($acc_id, $text, $pdo);
                                            } else {
                                                $text = " ble drept av en ukjent spiller";

                                                last_event($acc_id, $text, $pdo);
                                            }

                                            take_bullets($_SESSION['ID'], $bullets, $pdo);
                                            kill_user($acc_id, $pdo);
                                            kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

                                            if ($money_on_hand > 0) {
                                                echo feedback("Du drepte " . $username . ". Du fikk " . $ten_percent_of_exp . " exp og " . number($money_on_hand) . " kr for drapet. Det ble brukt " . number($bullets) . " kuler", "success");
                                            } else {
                                                echo feedback("Du drepte " . $username . ". Spilleren hadde ingen penger på hånden og du fikk dermed ingen penger for drapet. Men du fikk " . $ten_percent_of_exp . " exp. Det ble brukt " . number($bullets) . " kuler", "success");
                                            }
                                        } else {
                                            if ($amount_fp_remove > $def) {
                                                take_bullets($_SESSION['ID'], $bullets, $pdo);
                                                kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                                give_half_hp($_SESSION['ID'], $pdo);

                                                take_fp($def, $acc_id, $pdo);
                                                $to = $acc_id;
                                                $text = "Du har blitt angrepet av " . username_plain($_SESSION['ID'], $pdo) . " og mistet " . number($def) . " forsvarspoeng";
                                                send_notification($to, $text, $pdo);

                                                echo feedback("Du feilet på drapet på " . $username . ", men gjorde skade på spilleren", "error");
                                            } else {
                                                take_bullets($_SESSION['ID'], $bullets, $pdo);
                                                kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                                give_half_hp($_SESSION['ID'], $pdo);

                                                take_fp($amount_fp_remove, $acc_id, $pdo);
                                                $to = $acc_id;
                                                $text = "Du har blitt angrepet av " . username_plain($_SESSION['ID'], $pdo) . "og mistet " . number($amount_fp_remove) . " forsvarspoeng";
                                                send_notification($to, $text, $pdo);

                                                echo feedback("Du feilet på drapet på " . $username . ", men gjorde skade på spilleren.", "error");
                                            }
                                        }
                                    } else {
                                        kill_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                                        take_bullets($_SESSION['ID'], $_POST['bullet_amount'], $pdo);
                                        give_half_hp($_SESSION['ID'], $pdo);
                                        $user_id = get_acc_id($username, $pdo);

                                        if (player_in_bunker($KILL_AS_row['AS_id'], $pdo)) {
                                            $query = $pdo->prepare("SELECT BUNACT_cooldown FROM bunker_active WHERE BUNACT_acc_id = ?");
                                            $query->execute(array($KILL_AS_row['AS_id']));
                                            $row = $query->fetch(PDO::FETCH_ASSOC);

                                            $when_in_bunker = $row['BUNACT_cooldown'] - 86400;

                                            if ((time() - $when_in_bunker) <= 30) {
                                                $median = time() - $when_in_bunker;
                                                send_notification(
                                                    $KILL_AS_row['AS_id'],
                                                    username_plain($_SESSION['ID'], $pdo) . ' 
                                                    forsøkte å drepe deg i 
                                                    ' . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . ' og var ' . $median . ' sekunder for sen.',
                                                    $pdo
                                                );
                                            } else {
                                                send_notification(
                                                    $KILL_AS_row['AS_id'],
                                                    username_plain($_SESSION['ID'], $pdo) . ' 
                                                    forsøkte å drepe deg i 
                                                    ' . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . '. Du var i bunker.',
                                                    $pdo
                                                );
                                            }
                                        } else {
                                            send_notification(
                                                $KILL_AS_row['AS_id'],
                                                username_plain($_SESSION['ID'], $pdo) . ' 
                                                forsøkte å drepe deg i 
                                                ' . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . '',
                                                $pdo
                                            );
                                        }

                                        echo feedback("Du feilet på drapet på " . $username . ".", "error");
                                    }
                                } else {
                                    echo feedback("Brukeren er medlem av ledelsen og kan derfor ikke drepes.", "fail");
                                }
                            }
                        } else {
                            echo feedback("Brukeren eksisterer ikke", "fail");
                        }
                    }
                }
            } else {
                echo feedback('Ugyldig kule-verdi', "error");
            }
        }
    }
}

if (isset($_POST['nullstill'])) {
    if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= 100) {
        $sql = "UPDATE cooldown SET CD_kill = ? WHERE CD_acc_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([0, $_SESSION['ID']]);

        take_poeng($_SESSION['ID'], 100, $pdo);

        echo feedback('Du nullstillet ventetiden for 100 poeng!', 'success');
    } else {
        echo feedback('Ikke nok poeng', 'error');
    }
}

?>
<div class="col-8 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/drep.png">

        <?php if (kill_ready($_SESSION['ID'], $pdo)) {
            $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
            $stmt->execute(array(
                ':cd_id' => $_SESSION['ID']
            ));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $time_left = $row['CD_kill'] - time();

            $minutes = floor($time_left / 60);
            $seconds = $time_left - $minutes * 60;

            echo feedback('Du har nylig utført drap. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan utføre et nytt drap.', 'cooldown');

        ?>


            <form method="post">
                <input type="submit" name="nullstill" value="Nullstill drapstimeren for 100 poeng" />
            </form>

            <script>
                timeleft(<?php echo $time_left; ?>, "countdowntimer");
            </script>

        <?php
        } else {
        ?>
            <form method="post">
                <div class="col-6">
                    <h4>Hvem ønsker du å drepe?</h4>
                    <p class="description">Administrator og moderator ikke drepes.</p>
                    <input type="text" name="username" placeholder="Brukernavn...">
                </div>
                <div class="col-6">
                    <h4>Hvor mange kuler vil du bruke?</h4>
                    <p class="description">Du har: <?php echo number(AS_session_row($_SESSION['ID'], 'AS_bullets', $pdo)); ?></p>
                    <input type="text" name="bullet_amount" id="number" placeholder="Antall kuler...">
                </div>
                <p class="description">
                    Husk at når du angriper vil ditt forsvar bli halvvert i 4 timer.<br>
                    Antall kuler som trengs varierer, sjekk <a href="?side=drap&p=kalkulator">kalkulator</a></p>
                <input type="submit" name="kill" style="width: 100%;" value="Drep spiller">
            </form>
        <?php } ?>
    </div>
</div>

<script>
    number_space("#number");
</script>