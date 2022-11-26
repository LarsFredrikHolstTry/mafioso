<?php

function total_crimes_today_user($id, $pdo)
{
    $query = $pdo->prepare("SELECT sum(DAUTUS_crime) as count FROM dagens_utfordring_user WHERE DAUTUS_acc_id = " . $id . "");
    $query->execute();
    $row_ver = $query->fetch(PDO::FETCH_ASSOC);

    return $row_ver['count'] ?? NULL;
}

$drug_crime = $useLang->crime->actions->stealDrugs;
$rob_bank = $useLang->crime->actions->robBank;
$city = AS_session_row($_SESSION['ID'], 'AS_city', $pdo);

if ($city == 0) {
    $crime[0] = $useLang->crime->actions->cities->oslo->alt0;
    $crime[1] = $useLang->crime->actions->cities->oslo->alt1;
    $crime[2] = $useLang->crime->actions->cities->oslo->alt2;
    $crime[3] = $useLang->crime->actions->cities->oslo->alt3;
    $crime[4] = $useLang->crime->actions->cities->oslo->alt4;
    $crime[5] = $useLang->crime->actions->cities->oslo->alt5;
    $crime[6] = $useLang->crime->actions->cities->oslo->alt6;
    $crime[7] = $drug_crime;
    $crime[8] = $rob_bank;
} elseif ($city == 1) {
    $crime[0] = $useLang->crime->actions->cities->lasVegas->alt0;
    $crime[1] = $useLang->crime->actions->cities->lasVegas->alt1;
    $crime[2] = $useLang->crime->actions->cities->lasVegas->alt2;
    $crime[3] = $useLang->crime->actions->cities->lasVegas->alt3;
    $crime[4] = $useLang->crime->actions->cities->lasVegas->alt4;
    $crime[5] = $useLang->crime->actions->cities->lasVegas->alt5;
    $crime[6] = $useLang->crime->actions->cities->lasVegas->alt6;
    $crime[7] = $drug_crime;
    $crime[8] = $rob_bank;
} elseif ($city == 2) {
    $crime[0] = $useLang->crime->actions->cities->london->alt0;
    $crime[1] = $useLang->crime->actions->cities->london->alt1;
    $crime[2] = $useLang->crime->actions->cities->london->alt2;
    $crime[3] = $useLang->crime->actions->cities->london->alt3;
    $crime[4] = $useLang->crime->actions->cities->london->alt4;
    $crime[5] = $useLang->crime->actions->cities->london->alt5;
    $crime[6] = $useLang->crime->actions->cities->london->alt6;
    $crime[7] = $drug_crime;
    $crime[8] = $rob_bank;
} elseif ($city == 3) {
    $crime[0] = $useLang->crime->actions->cities->berlin->alt0;
    $crime[1] = $useLang->crime->actions->cities->berlin->alt1;
    $crime[2] = $useLang->crime->actions->cities->berlin->alt2;
    $crime[3] = $useLang->crime->actions->cities->berlin->alt3;
    $crime[4] = $useLang->crime->actions->cities->berlin->alt4;
    $crime[5] = $useLang->crime->actions->cities->berlin->alt5;
    $crime[6] = $useLang->crime->actions->cities->berlin->alt6;
    $crime[7] = $drug_crime;
    $crime[8] = $rob_bank;
} elseif ($city == 4) {
    $crime[0] = $useLang->crime->actions->cities->beijing->alt0;
    $crime[1] = $useLang->crime->actions->cities->beijing->alt1;
    $crime[2] = $useLang->crime->actions->cities->beijing->alt2;
    $crime[3] = $useLang->crime->actions->cities->beijing->alt3;
    $crime[4] = $useLang->crime->actions->cities->beijing->alt4;
    $crime[5] = $useLang->crime->actions->cities->beijing->alt5;
    $crime[6] = $useLang->crime->actions->cities->beijing->alt6;
    $crime[7] = $drug_crime;
    $crime[8] = $rob_bank;
}


function crime_exp($nr, $pdo)
{
    $crime_exp[0] = 1;
    $crime_exp[1] = 2;
    $crime_exp[2] = 3;
    $crime_exp[3] = 4;
    $crime_exp[4] = 5;
    $crime_exp[5] = 6;
    $crime_exp[6] = 7;
    $crime_exp[7] = 1;
    $crime_exp[8] = 1;

    return $crime_exp[$nr];
}

function crime_payout($nr)
{
    $crime_payout[0] = mt_rand(10, 50);
    $crime_payout[1] = mt_rand(30, 150);
    $crime_payout[2] = mt_rand(50, 250);
    $crime_payout[3] = mt_rand(100, 350);
    $crime_payout[4] = mt_rand(150, 450);
    $crime_payout[5] = mt_rand(450, 850);
    $crime_payout[6] = mt_rand(950, 1250);
    $crime_payout[7] = 1;
    $crime_payout[8] = mt_rand(10000, 20000);

    return $crime_payout[$nr];
}

function crime_payout_from($nr)
{
    $crime_payout[0] = 10;
    $crime_payout[1] = 30;
    $crime_payout[2] = 50;
    $crime_payout[3] = 100;
    $crime_payout[4] = 150;
    $crime_payout[5] = 450;
    $crime_payout[6] = 950;
    $crime_payout[7] = 1;
    $crime_payout[8] = 10000;

    return $crime_payout[$nr];
}

function crime_payout_to($nr)
{
    $crime_payout[0] = 50;
    $crime_payout[1] = 150;
    $crime_payout[2] = 250;
    $crime_payout[3] = 350;
    $crime_payout[4] = 450;
    $crime_payout[5] = 850;
    $crime_payout[6] = 1250;
    $crime_payout[7] = 1;
    $crime_payout[8] = 20000;

    return $crime_payout[$nr];
}

function crime_cooldown($nr, $pdo)
{
    $crime_cd[0] = 15;
    $crime_cd[1] = 25;
    $crime_cd[2] = 40;
    $crime_cd[3] = 60;
    $crime_cd[4] = 80;
    $crime_cd[5] = 120;
    $crime_cd[6] = 165;
    $crime_cd[7] = 100;
    $crime_cd[8] = 150;
    return $crime_cd[$nr];
}

function crime_chance_boost($alternative)
{
    $crime_boost[0] = 10;
    $crime_boost[1] = 9;
    $crime_boost[2] = 8;
    $crime_boost[3] = 7;
    $crime_boost[4] = 6;
    $crime_boost[5] = 5;
    $crime_boost[6] = 4;
    $crime_boost[7] = 0;
    $crime_boost[8] = 0;

    return $crime_boost[$alternative];
}

function crime_starter($alternative)
{
    $crime_starter[0] = 45;
    $crime_starter[1] = 40;
    $crime_starter[2] = 35;
    $crime_starter[3] = 30;
    $crime_starter[4] = 25;
    $crime_starter[5] = 20;
    $crime_starter[6] = 15;
    $crime_starter[7] = 85;
    $crime_starter[8] = 75;

    return $crime_starter[$alternative];
}

function give_crime_chance($acc_id, $city, $alternative, $pdo)
{
    $stmt = $pdo->prepare("SELECT CCH_chance FROM crime_chance WHERE CCH_acc_id = ? AND CCH_city = ? AND CCH_alternative = ?");
    $stmt->execute([$acc_id, $city, $alternative]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $crime_chance_starter = crime_starter($alternative) + crime_chance_boost($alternative);

        $sql = "INSERT INTO crime_chance (CCH_acc_id, CCH_city, CCH_alternative, CCH_chance) VALUES (?,?,?,?)";
        $pdo->prepare($sql)->execute([$acc_id, $city, $alternative, $crime_chance_starter]);
    } else {
        if ($row['CCH_chance'] <= 85) {
            $new_chance = $row['CCH_chance'] + crime_chance_boost($alternative);
            $sql = "UPDATE crime_chance SET CCH_chance=? WHERE CCH_acc_id=? AND CCH_city=? AND CCH_alternative=?";
            $pdo->prepare($sql)->execute([$new_chance, $acc_id, $city, $alternative]);
        }
    }
}

function get_crime_chance($acc_id, $city, $alternative, $pdo)
{
    $stmt = $pdo->prepare("SELECT CCH_chance FROM crime_chance WHERE CCH_acc_id = ? AND CCH_city = ? AND CCH_alternative = ?");
    $stmt->execute([$acc_id, $city, $alternative]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return crime_starter($alternative);
    } else {
        return $row['CCH_chance'] ?? NULL;
    }
}

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} elseif (!isset($_GET['side'])) {
    echo 'Ugyldig side';
} else {
    $side = $_GET['side'];

    if (isset($_GET['vellykket'])) {
        if (!is_numeric($_GET['p'])) {
            echo feedback($useLang->crime->feedbacks->error, "error");
        } elseif ($_GET['vellykket'] == 7) {
            echo feedback(str_replace('{money}', number($_GET['p']), $useLang->crime->feedbacks->successDrugs), 'success');
        } elseif ($_GET['vellykket'] == 8) {
            echo feedback(str_replace('{money}', number($_GET['p']), $useLang->crime->feedbacks->successHeist), 'success');
        } else {
            echo feedback(str_replace('{money}', number($_GET['p']), $useLang->crime->feedbacks->success), 'success');
        }

        if (isset($_GET['codep'])) {
            echo feedback("Jeg hører du har et oppdrag om å avkoble en bombe. Tall plassering " . ($_GET['codep'] + 1) . " er " . $_GET['code'] . ". Lykke til!", "blue");
        }
    }

    if (isset($_GET['feilet'])) {
        if (!is_numeric($_GET['feilet'])) {
            echo feedback($useLang->crime->feedbacks->error, 'error');
        } elseif (isset($_GET['jail'])) {
            echo feedback($useLang->crime->feedbacks->failedJail, 'error');
        } else {
            echo feedback($useLang->crime->feedbacks->failed, 'error');
        }
    }

    $heist_id = get_my_heistID($_SESSION['ID'], $pdo);

    if (isset($_GET['alt'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'alternativ: ' . $_GET['alt'] . '', $pdo);
        $alt = $_GET['alt'];
        $legal =   array(0, 1, 2, 3, 4, 5, 6, 7, 8);
        $chance =  mt_rand(1, 100);

        $percentage = get_crime_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);

        if ($percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) < 0) {
            $percentage = 0;
        } else {
            $percentage = $percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
        }

        $cooldown = crime_cooldown($alt, $pdo) + time();

        if (!in_array($alt, $legal) || !is_numeric($alt) || $alt == null) {
            user_log($_SESSION['ID'], $_GET['side'], 'ugyldig verdi', $pdo);
            echo feedback("Ugyldig verdi.", "fail");
        } elseif ($percentage >= $chance) {
            if (crime_ready($_SESSION['ID'], $pdo)) {
                user_log($_SESSION['ID'], $_GET['side'], 'ventetid', $pdo);
                echo feedback("Ventetid!", "error");
            } else {
                $payout = crime_payout($alt);

                update_konk($_GET['side'], 1, $_SESSION['ID'], $pdo);

                update_dagens_utfordring($_SESSION['ID'], 0, $pdo);

                give_exp($_SESSION['ID'], crime_exp($alt, $pdo), $pdo);

                crime_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                give_crime_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);

                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                update_crime($_SESSION['ID'], 1, $pdo);

                if (mt_rand(0, 100) == 0) {
                    update_things($_SESSION['ID'], 40, $pdo);
                    if (notificationSettings('dirkesett', $_SESSION['ID'], $pdo)) {
                        send_notification($_SESSION['ID'], "Du fant et dirkesett når du utførte en kriminell handling!", $pdo);
                    }
                }

                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 2 || AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 12) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }


                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 31 && $alt == 5 && AS_session_row($_SESSION['ID'], 'AS_city', $pdo) == 1) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + $payout, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }

                if(date('m') == 12){
                    $query = $pdo->prepare("SELECT * FROM christmas WHERE CHR_acc_id = ? AND CHR_day = ?");
                    $query->execute(array($_SESSION['ID'], date('j')));
                    $CHR_today = $query->fetch(PDO::FETCH_ASSOC);

                    if($CHR_today){
                        $sql = "UPDATE christmas SET CHR_counter = CHR_counter + 1 WHERE CHR_acc_id = ? AND CHR_day = ? ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$_SESSION['ID'], date('j')]);
                    } else {
                        $sql = "INSERT INTO christmas (CHR_day, CHR_acc_id, CHR_counter) VALUES (?,?,?)";
                        $pdo->prepare($sql)->execute([date('j'), $_SESSION['ID'], 1]);
                    }
                }

                if ($alt == 7) {
                    give_weed($_SESSION['ID'], crime_payout($alt), $pdo);

                    if (active_heist($_SESSION['ID'], $pdo)) {
                        if (heist_row($heist_id, 'HEIST_type', $pdo) == 0 && heist_row($heist_id, 'HEIST_status', $pdo) == 1 && mt_rand(0, 1) == 1) {
                            $number = mt_rand(0, 7);
                            $code = heist_row($heist_id, 'HEIST_info', $pdo);
                            $code = $code[$number];

                            user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                            header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout . "&codep=" . $number . "&code=" . $code);
                        } else {
                            user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                            header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout);
                        }
                    } else {
                        user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                        header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout);
                    }
                } elseif ($alt == 8) {
                    if (active_heist($_SESSION['ID'], $pdo) && heist_row($heist_id, 'HEIST_type', $pdo) == 1) {

                        $sql = "UPDATE heist SET HEIST_info = HEIST_info + ? WHERE HEIST_id = ? ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$payout, $heist_id]);

                        user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                        header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout);
                    } else {
                        echo feedback("Du er ikke i et aktivt heist", "error");
                    }
                } else {
                    give_money($_SESSION['ID'], $payout, $pdo);

                    give_territorium_money(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $payout * 0.1, $pdo);

                    if (active_heist($_SESSION['ID'], $pdo)) {
                        if (heist_row($heist_id, 'HEIST_type', $pdo) == 0 && heist_row($heist_id, 'HEIST_status', $pdo) == 1 && mt_rand(0, 1) == 1) {
                            $number = mt_rand(0, 6);
                            $code = heist_row($heist_id, 'HEIST_info', $pdo);
                            $code = $code[$number];

                            user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                            header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout . "&codep=" . $number . "&code=" . $code);
                        } else {
                            user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                            header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout);
                        }
                    } else {
                        user_log($_SESSION['ID'], $_GET['side'], 'vellykket', $pdo);
                        header("Location: ?side=kriminalitet&vellykket=" . $alt . "&p=" . $payout);
                    }
                }
            }
        } else {
            crime_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
            give_crime_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $alt, $pdo);
            update_crime($_SESSION['ID'], 0, $pdo);

            if (mt_rand(1, 4) > 2) {
                user_log($_SESSION['ID'], $_GET['side'], 'feilet', $pdo);
                header("Location: ?side=kriminalitet&feilet=" . $alt . "");
            } else {
                user_log($_SESSION['ID'], $_GET['side'], 'feilet = fengsel', $pdo);
                header("Location: ?side=kriminalitet&feilet=" . $alt . "&jail=true");
                put_in_jail($_SESSION['ID'], $cooldown, 'Feilet en kriminell handling', $pdo);
            }
        }
    }

    if (get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) > 50) {
        echo feedback("Byskatten i denne byen er veldig høy! Det anbefales at du reiser til en by med mindre byskatt.", "fail");
    }

?>
    <div class="col-12 single">
        <div class="col-8" style="padding-top: 0px;">
            <div class="content" id="heatmap">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                <?php

                if (crime_ready($_SESSION['ID'], $pdo)) {
                    $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                    $stmt->execute(array(
                        ':cd_id' => $_SESSION['ID']
                    ));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    $time_left = $row['CD_crime'] - time();

                    $minutes = floor($time_left / 60);
                    $seconds = $time_left - $minutes * 60;

                    echo feedback('Du har nylig utført en kriminalitet. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan utføre en ny kriminalitet.', 'cooldown'); ?>
                    <script>
                        timeleft(<?php echo $time_left; ?>, "countdowntimer");
                    </script>

                <?php

                } else {

                ?>
                    <p style="text-align: center;" class="description">Byskatten i denne byen er <?php echo output_city_tax(get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?></p>
                    <table>
                        <tr>
                            <th style="width: 40%;"><?php echo $useLang->crime->header->action; ?></th>
                            <th style="width: 20%;"><?php echo $useLang->crime->header->cooldown; ?></th>
                            <th style="width: 20%;"><?php echo $useLang->crime->header->chance; ?></th>
                            <th style="width: 20%;"><?php echo $useLang->crime->header->payment; ?></th>
                        </tr>

                        <?php

                        if (active_heist($_SESSION['ID'], $pdo) && heist_row($heist_id, 'HEIST_type', $pdo) == 1 && heist_row($heist_id, 'HEIST_status', $pdo) == 1) {
                            $crime_amount = 9;
                        } else {
                            $crime_amount = 8;
                        }
                        for ($i = 0; $i < $crime_amount; $i++) {

                        ?>
                            <tr class="cursor_hover clickable-row" data-href="?side=kriminalitet&alt=<?php echo $i; ?>">
                                <td><?php echo $crime[$i]; ?></td>
                                <td><?php echo crime_cooldown($i, $pdo); ?>s</td>
                                <td><?php

                                    $percentage = get_crime_chance($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $i, $pdo);

                                    if ($percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) < 0) {
                                        $percentage = 0;
                                    } else {
                                        $percentage = $percentage - get_city_tax(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);
                                    }

                                    echo colorize_chances($percentage);

                                    ?></td>
                                <td><?php
                                    if ($i != 7) {
                                        $from = str_replace('{value}', number(crime_payout_from($i)), $useLang->index->money);
                                        $to = str_replace('{value}', number(crime_payout_to($i)), $useLang->index->money);
                                        echo $from  . ' - ' . $to;
                                    } else {
                                        echo '1g cannabis';
                                    }

                                    ?></td>
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
                    <li>Vellykkede krim utført i dag: <?php echo number(total_crimes_today_user($_SESSION['ID'], $pdo)); ?></li>
                    <br>
                    <li>Vellykkede krim utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_krim_v', $pdo)); ?></li>
                    <li>Mislykkede krim utført: <?php echo number(US_session_row($_SESSION['ID'], 'US_krim_m', $pdo)); ?></li>
                    <br>
                    <li>Totalt antall krim utført i dag av alle: <?php echo number(total_crimes_today($pdo)); ?></li>
                </ul>
            </div>
            <div class="content">
                <span class="small_header">Topp 10 flest vellykkede kriminalitet</span>
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
                    user_statistics.US_acc_id, (user_statistics.US_krim_v + 0) as US_krim_v
                FROM 
                    user_statistics
                INNER JOIN 
                    accounts
                ON 
                    user_statistics.US_acc_id = accounts.ACC_id
                WHERE NOT
                    accounts.ACC_type IN (5)
                ORDER BY 
                US_krim_v DESC
                LIMIT 10
                ');
                    $query->execute();
                    foreach ($query as $row) {
                        $i++;

                    ?>
                        <tr>
                            <td><?php echo $i; ?>.</td>
                            <td><?php echo ACC_username($row['US_acc_id'], $pdo); ?></td>
                            <td><?php echo number($row['US_krim_v']); ?> stk</td>
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

        $(document).ready(function() {
            $("#heatmap").click(function(e) {
                var elm = $(this);
                var xPos = e.pageX - elm.offset().left;
                var yPos = e.pageY - elm.offset().top;

                $.ajax({
                    type: 'post', // the method (could be GET btw)
                    url: 'app/kriminalitet/api/heatmap.php', // The file where my php code is
                    data: {
                        'x': xPos, // all variables i want to pass. In this case, only one.
                        'y': yPos // all variables i want to pass. In this case, only one.
                    },
                    success: function(data) { // in case of success get the output, i named data
                        // Succ(hehe)ess
                    }
                });
            });
        });
    </script>
<?php } ?>
