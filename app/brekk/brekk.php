<?php

function total_brekk_today_user($id, $pdo)
{
    $query = $pdo->prepare("SELECT sum(DAUTUS_brekk) as count FROM dagens_utfordring_user WHERE DAUTUS_acc_id = " . $id . "");
    $query->execute();
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    if (!isset($_GET['side'])) {
        echo 'Ugyldig side';
    } else {
        $side = $_GET['side'];


        if (isset($_GET['vellykket'])) {
            $legal = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33);
            if (!in_array($_GET['vellykket'], $legal)) {
                echo feedback("Ugyldig verdi", "error");
            } else {
                echo feedback("Du stjal " . thing($_GET['vellykket']) . "!", "success");
            }

            if (isset($_GET['codep'])) {
                echo feedback("Jeg hører du har et oppdrag om å avkoble en bombe. Tall plassering " . ($_GET['codep'] + 1) . " er " . $_GET['code'] . ". Lykke til!", "blue");
            }
        }

        if (isset($_GET['mislykket'])) {
            if (isset($_GET['jail'])) {
                echo feedback("Brekket var mislykket og du ble satt i fengsel", "error");
            } else {
                echo feedback("Brekket var mislykket", "error");
            }
        }

        function brekk_text($city, $nr)
        {
            if ($city == 0) {
                $brekk[0] = "Ran leilighet på Grønland";
                $brekk[1] = "Ran hus på Grünerløkka";
                $brekk[2] = "Ran villa på Nordstrand";
            } elseif ($city == 1) {
                $brekk[0] = "Ran nedslitt hus på Downtown East";
                $brekk[1] = "Ran hus på Huntridge";
                $brekk[2] = "Stjel ting fra villa på The Lakes";
            } elseif ($city == 2) {
                $brekk[0] = "Ran leilighet i St Giles' slummen";
                $brekk[1] = "Ran hus på Kensington";
                $brekk[2] = "Ran pent-house på Knightsbridge";
            } elseif ($city == 3) {
                $brekk[0] = "Ran leilighet på Mitte";
                $brekk[1] = "Ran hus på Kreuzberg";
                $brekk[2] = "Ran villa på Friedrichshain";
            } elseif ($city == 4) {
                $brekk[0] = "Stjel ting fra forlatt hus på East Chang'an Avenue";
                $brekk[1] = "Ran hus i Hutong";
                $brekk[2] = "Stjel ting fra Parkview Green";
            }
            return $brekk[$nr];
        }


        function brekk_cooldown($nr)
        {
            $brekk_cd[0] = 45;
            $brekk_cd[1] = 85;
            $brekk_cd[2] = 150;

            return $brekk_cd[$nr];
        }

        function brekk_exp($alt, $pdo)
        {
            $brekk_exp[0] = 1;
            $brekk_exp[1] = 3;
            $brekk_exp[2] = 5;

            return $brekk_exp[$alt];
        }

        function brekk_payout($alt, $pdo)
        {
            if ($alt == 0) {
                if (mt_rand(0, 100) > 99) {
                    return mt_rand(29, 30); // Energidrikk og hemmelig kiste
                } else {
                    return mt_rand(0, 9);
                }
            } elseif ($alt == 1) {
                if (mt_rand(0, 100) > 99) {
                    return mt_rand(29, 30); // Energidrikk og hemmelig kiste
                } else {
                    return mt_rand(10, 19);
                }
            } elseif ($alt == 2) {
                if (mt_rand(0, 100) > 99) {
                    return mt_rand(29, 30); // Energidrikk og hemmelig kiste
                } else {
                    return mt_rand(20, 28);
                }
            }
        }

        function rob_chance_boost($alternative)
        {
            $rob_boost[0] = 10;
            $rob_boost[1] = 9;
            $rob_boost[2] = 8;

            return $rob_boost[$alternative];
        }

        function rob_starter($alternative)
        {
            $rob_starter[0] = 25;
            $rob_starter[1] = 20;
            $rob_starter[2] = 15;

            return $rob_starter[$alternative];
        }

        function give_rob_chance($acc_id, $city, $alternative, $pdo)
        {
            $stmt = $pdo->prepare("SELECT RCH_chance FROM rob_chance WHERE RCH_acc_id = ? AND RCH_city = ? AND RCH_alternative = ?");
            $stmt->execute([$acc_id, $city, $alternative]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                $rob_chance_starter = rob_starter($alternative) + rob_chance_boost($alternative);

                $sql = "INSERT INTO rob_chance (RCH_acc_id, RCH_city, RCH_alternative, RCH_chance) VALUES (?,?,?,?)";
                $pdo->prepare($sql)->execute([$acc_id, $city, $alternative, $rob_chance_starter]);
            } else {
                if ($row['RCH_chance'] <= 85) {
                    $new_chance = $row['RCH_chance'] + rob_chance_boost($alternative);
                    $sql = "UPDATE rob_chance SET RCH_chance=? WHERE RCH_acc_id=? AND RCH_city=? AND RCH_alternative=?";
                    $pdo->prepare($sql)->execute([$new_chance, $acc_id, $city, $alternative]);
                }
            }
        }

        function get_rob_chance($acc_id, $city, $alternative, $pdo)
        {
            $stmt = $pdo->prepare("SELECT RCH_chance FROM rob_chance WHERE RCH_acc_id = ? AND RCH_city = ? AND RCH_alternative = ?");
            $stmt->execute([$acc_id, $city, $alternative]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return rob_starter($alternative);
            } else {
                return $row['RCH_chance'] ?? NULL;
            }
        }

            if (isset($_GET['alt'])) {
                user_log($_SESSION['ID'], $_GET['side'], 'alternativ: ' . $_GET['alt'] . '', $pdo);
                $legal =   array(0, 1, 2);
                $chance =  mt_rand(1, 100);
                $alt = $_GET['alt'];
                $cooldown = brekk_cooldown($alt) + time();

                $percentage = get_rob_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);

                if ($percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) < 0) {
                    $percentage = 0;
                } else {
                    $percentage = $percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
                }

                if ($full_storage) {
                    user_log($_SESSION['ID'], $_GET['side'], 'fullt lager', $pdo);
                    echo feedback("Du har kun plass til $max_storage ting i ditt lager. <a href='?side=poeng'>Du kan gjøre noe med det her</a>", "blue");
                } elseif (!in_array($alt, $legal) || !is_numeric($alt)) {
                    user_log($_SESSION['ID'], $_GET['side'], 'ugyldig verdi', $pdo);
                    echo feedback("Ugyldig verdi.", "fail");
                } elseif ($percentage > $chance) {
                    if ((brekk_ready($_SESSION['ID'], $pdo))) {
                        user_log($_SESSION['ID'], $_GET['side'], 'ventetid', $pdo);
                        echo feedback("Ventetid!", "error");
                    } else {
                        update_konk($_GET['side'], 1, $_SESSION['ID'], $pdo);

                        if (active_konk($pdo)) {
                            if (mt_rand(0, 10) == 5) {
                                $amount = mt_rand(1, 3);
                                give_poeng($_SESSION['ID'], $amount, $pdo);
                                $text = "Du fant " . number($amount) . " poeng da du utførte et brekk!";
                                send_notification($_SESSION['ID'], $text, $pdo);
                            }
                        }

                        $brekk_id = brekk_payout($alt, $pdo);

                        update_dagens_utfordring($_SESSION['ID'], 2, $pdo);

                        give_exp($_SESSION['ID'], brekk_exp($alt, $pdo), $pdo);
                        brekk_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                        update_things($_SESSION['ID'], $brekk_id, $pdo);
                        give_rob_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);
                        check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);
                        update_brekk($_SESSION['ID'], 1, $pdo);

                        give_territorium_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), thing_price($brekk_id) * 0.1, $pdo);

                        if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 4) {
                            mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                        }

                        if (active_heist($_SESSION['ID'], $pdo)) {
                            $heist_id = get_my_heistID($_SESSION['ID'], $pdo);

                            if (heist_row($heist_id, 'HEIST_type', $pdo) == 0 && heist_row($heist_id, 'HEIST_status', $pdo) == 1 && mt_rand(0, 1) == 1) {
                                $number = mt_rand(0, 3);
                                $code = heist_row($heist_id, 'HEIST_info', $pdo);
                                $code = $code[$number];

                                user_log($_SESSION['ID'], $_GET['side'], 'ugyldig vellykket', $pdo);
                                header("Location: ?side=brekk&vellykket=" . $brekk_id . "&codep=" . $number . "&code=" . $code);
                            } else {
                                user_log($_SESSION['ID'], $_GET['side'], 'ugyldig vellykket', $pdo);
                                header("Location: ?side=brekk&vellykket=" . $brekk_id);
                            }
                        } else {
                            user_log($_SESSION['ID'], $_GET['side'], 'ugyldig vellykket', $pdo);
                            header("Location: ?side=brekk&vellykket=" . $brekk_id);
                        }
                    }
                } else {
                    brekk_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                    give_rob_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);
                    update_brekk($_SESSION['ID'], 0, $pdo);

                    if (mt_rand(1, 4) > 2) {
                        user_log($_SESSION['ID'], $_GET['side'], 'ugyldig mislykket', $pdo);
                        header("Location: ?side=brekk&mislykket");
                    } else {
                        user_log($_SESSION['ID'], $_GET['side'], 'ugyldig mislykket = fengsel', $pdo);
                        header("Location: ?side=brekk&mislykket&jail=true");
                        put_in_jail($_SESSION['ID'], $cooldown, 'Feilet brekk', $pdo);
                    }
                }
            }

            if (get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > 50) {
                echo feedback("Byskatten i denne byen er veldig høy! Det anbefales at du reiser til en by med mindre byskatt.", "fail");
            }

            // Fullt lager
            if ($full_storage) {
                echo feedback("Lageret ditt er full! Du kan enten kjøpe mer plass på <a href='?side=poeng'>poengsiden</a> eller selge ting på <a href='?side=lager'>lageret</a>", "fail");
            }

        ?>
            <div class="col-12 single">
                <div class="col-8" style="padding-top: 0px;">
                    <div class="content">
                    <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                        <?php
                            if (brekk_ready($_SESSION['ID'], $pdo)) {
                                $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                                $stmt->execute(array(
                                    ':cd_id' => $_SESSION['ID']
                                ));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $time_left = $row['CD_brekk'] - time();

                                $minutes = floor($time_left / 60);
                                $seconds = $time_left - $minutes * 60;

                                echo feedback('Du har nylig utført et brekk. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan utføre et nytt brekk.', 'cooldown'); ?>
                                <script>
                                    timeleft(<?php echo $time_left; ?>, "countdowntimer");
                                </script>

                            <?php

                            } else {

                        ?>
                        <p style="text-align: center;" class="description">Byskatten i denne byen er <?php echo output_city_tax(get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?></p>
                        <table>
                            <tr>
                                <th style="width: 40%;">Handling</th>
                                <th style="width: 20%;">Ventetid</th>
                                <th style="width: 20%;">Sjanse</th>
                            </tr>

                            <?php

                            $brekk_amount = 3;
                            for ($i = 0; $i < $brekk_amount; $i++) {

                            ?>
                                <tr class="cursor_hover clickable-row" data-href="?side=brekk&alt=<?php echo $i; ?>">
                                    <td><?php echo brekk_text(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $i); ?></td>
                                    <td><?php echo brekk_cooldown($i, $pdo); ?>s</td>
                                    <td><?php

                                        $percentage = get_rob_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $i, $pdo);

                                        if ($percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) < 0) {
                                            echo $percentage = 0;
                                        } else {
                                            echo $percentage = $percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
                                        }

                                        ?>%</td>
                                </tr>
                            <?php } ?>
                        </table>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-4" style="padding-top: 0px;">
                    <div class="content">
                        <span class="small_header">Statistikk</span>
                        <ul>
                            <li>Vellykkede brekk utført i dag: <?php echo number(total_brekk_today_user($_SESSION['ID'], $pdo)); ?></li>
                            <br>
                            <li>Vellykkede brekk utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_brekk_v', $pdo)); ?></li>
                            <li>Mislykkede brekk utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_brekk_m', $pdo)); ?></li>
                            <br>
                            <li>Totalt antall brekk utført i dag av alle: <?php echo number(total_brekk_today($pdo)); ?></li>
                        </ul>
                    </div>
                    <div class="content">
                    <span class="small_header">Topp 10 flest vellykkede brekk</span>
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
                    user_statistics.US_acc_id, (user_statistics.US_brekk_v + 0) as US_brekk_v
                FROM 
                    user_statistics
                INNER JOIN 
                    accounts
                ON 
                    user_statistics.US_acc_id = accounts.ACC_id
                WHERE NOT
                    accounts.ACC_type IN (5)
                ORDER BY 
                US_brekk_v DESC
                LIMIT 10
                ');
                $query->execute();
                foreach ($query as $row) {
                    $i++;

                ?>
                <tr>
                    <td><?php echo $i; ?>.</td>
                    <td><?php echo ACC_username($row['US_acc_id'], $pdo); ?></td>
                    <td><?php echo number($row['US_brekk_v']); ?> stk</td>
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
<?php }
} ?>