<?php

function total_gta_today_user($id, $pdo)
{
    $query = $pdo->prepare("SELECT sum(DAUTUS_gta) as count FROM dagens_utfordring_user WHERE DAUTUS_acc_id = " . $id . "");
    $query->execute();
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} elseif (!isset($_GET['side'])) {
    echo 'Ugyldig side';
} else {

    $side = $_GET['side'];

    function gta_text($city, $nr)
    {
        if ($city == 0) {
            $gta[0] = "Stjel bil fra Gunerius";
            $gta[1] = "Stjel bil fra Plaza";
            $gta[2] = "Stjel bil fra AUTO XO";
        } elseif ($city == 1) {
            $gta[0] = "Stjel bil fra AXA Auto Care";
            $gta[1] = "Stjel bil fra Desert Oasis Imports";
            $gta[2] = "Stjel bil fra Caesars Palace";
        } elseif ($city == 2) {
            $gta[0] = "Stjel bil fra Cheapside Auto Motors";
            $gta[1] = "Stjel bil fra East Londn Auto";
            $gta[2] = "Stjel bil fra Kings Motors";
        } elseif ($city == 3) {
            $gta[0] = "Stjel bil fra Classic Remise";
            $gta[1] = "Stjel bil fra Autoland";
            $gta[2] = "Stjel bil fra Car Selection Premium";
        } elseif ($city == 4) {
            $gta[0] = "Stjel bil fra Northstar Used Cars";
            $gta[1] = "Stjel bil fra Autonation";
            $gta[2] = "Stjel bil fra Luxury Import Cars";
        }
        return $gta[$nr];
    }

    $gta_cd[0] = 65;
    $gta_cd[1] = 105;
    $gta_cd[2] = 180;

    $gta_exp[0] = 1;
    $gta_exp[1] = 3;
    $gta_exp[2] = 5;

    function gta_payout($alt, $hasDirkeSett)
    {
        if ($alt == 0) {
            return mt_rand(0, 5);
        } elseif ($alt == 1) {
            return mt_rand(6, 11);
        } elseif ($alt == 2) {
            if (mt_rand(0, !$hasDirkeSett ? 300 : 150) == 69) {
                return 18;
            } else {
                return mt_rand(12, 17);
            }
        }
    }


    function gta_chance_boost($alternative)
    {
        $gta_boost[0] = 10;
        $gta_boost[1] = 9;
        $gta_boost[2] = 8;

        return $gta_boost[$alternative];
    }

    function gta_starter($alternative)
    {
        $gta_starter[0] = 25;
        $gta_starter[1] = 20;
        $gta_starter[2] = 15;

        return $gta_starter[$alternative];
    }

    function give_gta_chance($acc_id, $city, $alternative, $pdo)
    {
        $stmt = $pdo->prepare("SELECT GCH_chance FROM gta_chance WHERE GCH_acc_id = ? AND GCH_city = ? AND GCH_alternative = ?");
        $stmt->execute([$acc_id, $city, $alternative]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $gta_chance_starter = gta_starter($alternative) + gta_chance_boost($alternative);

            $sql = "INSERT INTO gta_chance (GCH_acc_id, GCH_city, GCH_alternative, GCH_chance) VALUES (?,?,?,?)";
            $pdo->prepare($sql)->execute([$acc_id, $city, $alternative, $gta_chance_starter]);
        } else {
            if ($row['GCH_chance'] <= 85) {
                $new_chance = $row['GCH_chance'] + gta_chance_boost($alternative);
                $sql = "UPDATE gta_chance SET GCH_chance=? WHERE GCH_acc_id=? AND GCH_city=? AND GCH_alternative=?";
                $pdo->prepare($sql)->execute([$new_chance, $acc_id, $city, $alternative]);
            }
        }
    }

    function get_gta_chance($acc_id, $city, $alternative, $pdo)
    {
        $stmt = $pdo->prepare("SELECT GCH_chance FROM gta_chance WHERE GCH_acc_id = ? AND GCH_city = ? AND GCH_alternative = ?");
        $stmt->execute([$acc_id, $city, $alternative]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return gta_starter($alternative);
        } else {
            return $row['GCH_chance'] ?? NULL;
        }
    }

    if (isset($_GET['robbed_kamero'])) {
        $supercar[0] = "Lamborghini Aventador";
        $supercar[1] = "Ferrari 430 spider";
        $supercar[2] = "Rolls Royce Cullinan";
        $supercar[3] = "Ferrari GTC4Lusso";
        $supercar[4] = "Aston Martin DBS Superleggera";
        $supercar[5] = "Bentley Continental GT";

        echo feedback("Du klarte å stjele en " . $supercar[mt_rand(0, 5)] . " fra Kamera Camilo! Bilen blir levert til din oppdragsgiver.", "success");
    }

    if (isset($_GET['failed_kamero'])) {
        echo feedback("Du klarte ikke stjele å bil fra Kamera Camilo!", "error");
    }

    if (isset($_GET['vellykket'])) {
        $legal = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21);

        if (!in_array($_GET['vellykket'], $legal)) {
            echo feedback("Ugyldig verdi", "error");
        } else {
            echo feedback("Du stjal en " . car($_GET['vellykket']) . "!", "success");
        }

        if (isset($_GET['codep'])) {
            echo feedback("Jeg hører du har et oppdrag om å avkoble en bombe. Tall plassering " . ($_GET['codep'] + 1) . " er " . $_GET['code'] . ". Lykke til!", "blue");
        }
    }

    if (isset($_GET['mislykket'])) {
        if (isset($_GET['jail'])) {
            echo feedback("Biltyveriet var mislykket og du ble satt i fengsel", "error");
        } else {
            echo feedback("Biltyveriet var mislykket", "error");
        }
    }


    if (isset($_GET['rob_kamero'])) {
        if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 37 || AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 42) {
            user_log($_SESSION['ID'], $_GET['side'], 'stjel fra kamero', $pdo);
            $chance = mt_rand(1, 100);
            $cooldown = 500 + time();
            $exp = 3;

            gta_give_cooldown($_SESSION['ID'], $cooldown, $pdo);

            if ($chance < 70) {
                give_exp($_SESSION['ID'], $exp, $pdo);
                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                update_dagens_utfordring($_SESSION['ID'], 1, $pdo);
                update_gta($_SESSION['ID'], 1, $pdo);

                user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                header("Location: ?side=biltyveri&robbed_kamero");
            } else {
                update_gta($_SESSION['ID'], 0, $pdo);
                user_log($_SESSION['ID'], $_GET['side'], 'feilet', $pdo);
                header("Location: ?side=biltyveri&failed_kamero");
            }
        } else {
            echo feedback("Du er ikke på dette oppdraget enda", "error");
        }
    }

    if (isset($_POST['use_dirkesett'])) {
        $id = 40;
        if (active_dirkesett($_SESSION['ID'], $pdo)) {
            echo feedback('Du har allerede et aktivt dirkesett', 'fail');
        } elseif (amount_of_things($_SESSION['ID'], $id, $pdo) > 0) {
            $sql = "INSERT INTO dirkesett (DIRK_acc_id, DIRK_timeout) VALUES (?,?)";
            $pdo->prepare($sql)->execute([$_SESSION['ID'], time() + 3600]);

            $sql = "DELETE FROM things WHERE TH_type = $id AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
            $pdo->exec($sql);

            echo feedback('Du har aktivert dirkesett i 1 time', 'success');
        } else {
            echo feedback('Du har ikke valgt ting', 'fail');
        }
    }

    if (isset($_GET['alt'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'alternativ: ' . $_GET['alt'] . '', $pdo);
        $legal =   array(0, 1, 2);
        $chance =  mt_rand(1, 100);
        $alt = $_GET['alt'];
        $cooldown = $gta_cd[$alt] + time();

        $percentage = get_gta_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);

        if ($percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) < 0) {
            $percentage = 0;
        } else {
            $percentage = $percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
        }

        if ($full_garage) {
            user_log($_SESSION['ID'], $_GET['side'], 'max garasje', $pdo);
            echo feedback("Du har kun plass til $max_garage biler i din garasje. <a href='?side=poeng'>Du kan gjøre noe med det her</a>", "blue");
        } elseif (!in_array($alt, $legal) || !is_numeric($alt)) {
            user_log($_SESSION['ID'], $_GET['side'], 'ugyldig alternativ', $pdo);
            echo feedback("Ugyldig verdi.", "fail");
        } elseif ($percentage >= $chance) {
            if ((gta_ready($_SESSION['ID'], $pdo))) {
                user_log($_SESSION['ID'], $_GET['side'], 'ventetid', $pdo);
                echo feedback("Ventetid!", "error");
            } else {
                update_konk($_GET['side'], 1, $_SESSION['ID'], $pdo);

                $hasDirkeSett = false;

                if ($alt == 2 && mt_rand(0, !$hasDirkeSett ? 69 : 35) == 0) {
                    $car_id = 19;
                } elseif ($alt == 2 && mt_rand(0, !$hasDirkeSett ? 200 : 100) == 0) {
                    $car_id = 20;
                } elseif ($alt == 2 && mt_rand(0, !$hasDirkeSett ? 350 : 175) == 0) {
                    $car_id = 21;
                } else {
                    $car_id = gta_payout($alt, $hasDirkeSett);
                }

                give_exp($_SESSION['ID'], $gta_exp[$alt], $pdo);

                update_dagens_utfordring($_SESSION['ID'], 1, $pdo);

                gta_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                update_garage($_SESSION['ID'], $car_id, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
                give_gta_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);
                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
                update_gta($_SESSION['ID'], 1, $pdo);

                give_territorium_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), car_price($car_id) * 0.1, $pdo);

                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 3) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }

                if (mt_rand(0, 20) == 20) {
                    update_things($_SESSION['ID'], 31, $pdo);
                    send_notification($_SESSION['ID'], "Du fant gresskar når du utførte en kriminell handling!", $pdo);
                }

                if (active_heist($_SESSION['ID'], $pdo)) {
                    $heist_id = get_my_heistID($_SESSION['ID'], $pdo);

                    if (heist_row($heist_id, 'HEIST_type', $pdo) == 0 && heist_row($heist_id, 'HEIST_status', $pdo) == 1 && mt_rand(0, 1) == 1) {
                        $number = mt_rand(0, 3);
                        $code = heist_row($heist_id, 'HEIST_info', $pdo);
                        $code = $code[$number];
                        user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                        header("Location: ?side=biltyveri&vellykket=" . $car_id . "&codep=" . $number . "&code=" . $code);
                    } else {
                        user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                        header("Location: ?side=biltyveri&vellykket=" . $car_id);
                    }
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                    header("Location: ?side=biltyveri&vellykket=" . $car_id);
                }
            }
        } else {
            gta_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
            give_gta_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);
            update_gta($_SESSION['ID'], 0, $pdo);

            if (mt_rand(1, 4) > 2) {
                user_log($_SESSION['ID'], $_GET['side'], 'mislykket', $pdo);
                header("Location: ?side=biltyveri&mislykket");
            } else {
                user_log($_SESSION['ID'], $_GET['side'], 'mislykket = fengsel', $pdo);
                header("Location: ?side=biltyveri&mislykket&jail=true");
                put_in_jail($_SESSION['ID'], $cooldown, 'Feilet biltyveri', $pdo);
            }
        }
    }

    if (get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > 50) {
        echo feedback("Byskatten i denne byen er veldig høy! Det anbefales at du reiser til en by med mindre byskatt.", "fail");
    }

    // Full garasje
    if ($full_garage) {
        echo feedback("Garasjen din er full! Du kan enten kjøpe mer plass på <a href='?side=poeng'>poengsiden</a> eller selge biler i <a href='?side=garasje'>garasjen</a>", "fail");
    }


?>
    <div class="col-12 single">
        <div class="col-8" style="padding-top: 0px;">
            <div class="content">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">

                <?php
                if (gta_ready($_SESSION['ID'], $pdo)) {
                    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                    $stmt->execute(array(
                        ':cd_id' => $_SESSION['ID']
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $time_left = $row['CD_gta'] - time();

                    $minutes = floor($time_left / 60);
                    $seconds = $time_left - $minutes * 60;

                    echo feedback('Du har nylig utført et biltyveri. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan utføre et nytt biltyveri.', 'cooldown'); ?>
                    <script>
                        timeleft(<?php echo $time_left; ?>, "countdowntimer");
                    </script>

                <?php

                } else {

                ?>
                    <p style="text-align: center;" class="description">Byskatten i denne byen er <?php echo output_city_tax(get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?></p>
                    <table>
                        <tr>
                            <th style="width: 50%;">Handling</th>
                            <th style="width: 25%;">Ventetid</th>
                            <th style="width: 25%;">Sjanse</th>
                        </tr>

                        <?php

                        $gta_amount = 3;
                        for ($i = 0; $i < $gta_amount; $i++) {

                        ?>
                            <tr class="cursor_hover clickable-row" data-href="?side=biltyveri&alt=<?php echo $i; ?>">
                                <td><?php echo gta_text(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $i); ?></td>
                                <td><?php echo $gta_cd[$i]; ?>s</td>
                                <td><?php

                                    $percentage = get_gta_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $i, $pdo);

                                    if ($percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) < 0) {
                                        $percentage = 0;
                                    } else {
                                        $percentage = $percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
                                    }

                                    echo colorize_chances($percentage);

                                    ?></td>
                            </tr>
                        <?php } ?>


                        <?php if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 37 || AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 42) { ?>
                            <tr class="cursor_hover clickable-row" data-href="?side=biltyveri&rob_kamero">
                                <td>Stjel bil fra Kamero Camilla</td>
                                <td>500s</td>
                                <td>70%</td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
            <div class="content" style="margin-top:20px;">
                <h4>Benytt dirkesett</h4>
                <div style="margin: 10px; display: flex; align-items: center;">
                    <img style="margin-right: 20px;" src="img/action/actions/dirkesett.png" />
                    <p>Et dirkesett kan benyttes for å få 50% mer sjanse for å stjele bilene Rolls Royce Wraith,
                        1955 Mercedes 300SL Gullwing, Bugatti La Voiture Noire eller Rolls-Royce
                        Boat Tail. Et dirkesett kan du få ved å utføre kriminelle handlinger.
                        <br>
                        <br>
                        Et dirkesett varer i 1 time
                    </p>
                </div>
                <?php if (!active_dirkesett($_SESSION['ID'], $pdo)) { ?>
                    <form method="post">
                        <input type="submit" name="use_dirkesett" value="Bruk dirkesett" />
                    </form>
                <?php } else {
                    echo feedback('Du har et aktivt dirkesett til ' . date_to_text(dirkesett_timeout($_SESSION['ID'], $pdo)) . '', 'success');
                } ?>
            </div>
        </div>
        <div class="col-4" style="padding-top: 0px;">
            <div class="content">
                <span class="small_header">Statistikk</span>
                <ul>
                    <li>Vellykkede biltyveri utført i dag: <?php echo number(total_gta_today_user($_SESSION['ID'], $pdo)); ?></li>
                    <br>
                    <li>Vellykkede biltyveri utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_gta_v', $pdo)); ?></li>
                    <li>Mislykkede biltyveri utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_gta_m', $pdo)); ?></li>
                    <br>
                    <li>Totalt antall biltyveri utført i dag av alle: <?php echo number(total_gta_today($pdo)); ?></li>
                </ul>
            </div>
            <div class="content">
                <span class="small_header">Topp 10 flest vellykkede biltyveri</span>
                <table>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th>Bruker</th>
                        <th>Antall</th>
                    </tr>

                    <?php

                    $i = 0;

                    $query = $pdo->prepare('
                SELECT 
                    user_statistics.US_acc_id, (user_statistics.US_gta_v + 0) as US_gta_v
                FROM 
                    user_statistics
                INNER JOIN 
                    accounts
                ON 
                    user_statistics.US_acc_id = accounts.ACC_id
                WHERE NOT
                    accounts.ACC_type IN (5)
                ORDER BY 
                US_gta_v DESC
                LIMIT 10
                ');
                    $query->execute();
                    foreach ($query as $row) {
                        $i++;

                    ?>
                        <tr>
                            <td><?php echo $i; ?>.</td>
                            <td><?php echo ACC_username($row['US_acc_id'], $pdo); ?></td>
                            <td><?php echo number($row['US_gta_v']); ?> stk</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                $(".clickable-row").unbind("click"); //Fjern click-event-listener for alle .clickable-row's
                window.location = $(this).data("href");
            });
        });
    </script>
<?php } ?>
