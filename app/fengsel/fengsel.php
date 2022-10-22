<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} else {

    $exp = 2;

    if (isset($_GET['carl'])) {
        echo feedback("Det var for mange vakter på flyplassen og du ble satt i fengsel.", "error");
    }

    if (isset($_GET['brytut'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Bryter ut', $pdo);
        $brytut_id = $_GET['brytut'];

        if ($brytut_id == "kamero") {
            if (!player_in_jail($_SESSION['ID'], $pdo)) {
                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) != 43) {
                    echo feedback("Du er ikke på oppdraget", "error");
                } else {
                    if (mt_rand(0, 15) == 1) {
                        mission_update(1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);

                        echo feedback("Du brøt ut Kamero Camilla!", "success");
                    } else {
                        put_in_jail($_SESSION['ID'], time() + 120, 'Feilet å bryte ut Kamero Camilla', $pdo);

                        echo feedback("Du klarte ikke å bryte ut Kamero Camilla og må derfor selv i fengsel i 2 minutter", "error");
                    }
                }
            } else {
                echo feedback("Du er i fengsel og kan ikke bryte ut Kamero Camilla", "error");
            }
        } else {
            if (is_numeric($brytut_id) && player_in_jail($brytut_id, $pdo) && !player_in_jail($_SESSION['ID'], $pdo)) {
                if (mt_rand(0, 100) < 70) {
                    $sql = "DELETE FROM fengsel WHERE FENG_acc_id = " . $brytut_id . "";
                    $pdo->exec($sql);

                    $to = $brytut_id;
                    $text = username_plain($_SESSION['ID'], $pdo) . " hjalp deg med å bryte ut av fengsel og du er nå fri";
                    if (notificationSettings('outOfJail', $to, $pdo)) {
                        send_notification($to, $text, $pdo);
                    }

                    give_exp($_SESSION['ID'], $exp, $pdo);

                    check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                    update_utbrytninger($_SESSION['ID'], $pdo);

                    if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 41) {
                        mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                    }

                    update_konk($_GET['side'], 1, $_SESSION['ID'], $pdo);

                    user_log($_SESSION['ID'], $_GET['side'], 'Bryter ut: ' . ACC_username($brytut_id, $pdo) . '', $pdo);
                    echo feedback("Du klarte å bryte ut " . ACC_username($brytut_id, $pdo) . " fra fengselet!", "success");
                } else {
                    put_in_jail($_SESSION['ID'], time() + 120, 'Feilet å bryte ut ' . username_plain($brytut_id, $pdo) . '', $pdo);
                    user_log($_SESSION['ID'], $_GET['side'], 'Feiler og setter seg selv i fengsel', $pdo);
                    echo feedback("Du klarte ikke å bryte ut " . ACC_username($brytut_id, $pdo) . " og må derfor selv i fengsel i 2 minutter.", "error");
                }
            } else {
                user_log($_SESSION['ID'], $_GET['side'], 'Spiller ikke i fengsel', $pdo);
                echo feedback("Spilleren er ikke i fengsel", "error");
            }
        }
    }

    if (isset($_GET['buyoutsuccess'])) {
        echo feedback("Du kjøpte deg selv ut av fengsel", "success");
    }

    if (isset($_GET['buyouterror'])) {
        echo feedback("Du har ikke nok til å kjøpe deg selv ut av fengsel", "error");
    }

    $price_for_jail = 500000;
    $price_for_sell = 50000;

    if (isset($_POST['sell_jail'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Selger fengsel til staten', $pdo);
        if (get_fengselsdirektor(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {
            $sql = "DELETE FROM fengsel_direktor WHERE FENGDI_acc_id = " . $_SESSION['ID'] . "";
            $pdo->exec($sql);

            give_money($_SESSION['ID'], $price_for_sell, $pdo);
            user_log($_SESSION['ID'], $_GET['side'], 'Solgt til staten', $pdo);
            echo feedback("Du solgte fengselet til staten", "success");
        } else {
            user_log($_SESSION['ID'], $_GET['side'], 'Prøver å selge noen andre sitt fengsel', $pdo);
            echo feedback("Uventet feil", "error");
        }
    }

    if (isset($_POST['buy_jail'])) {
        if (jail_owner_already($_SESSION['ID'], $pdo)) {
            echo feedback('Du eier allerede et fengsel', 'error');
        } else {
            user_log($_SESSION['ID'], $_GET['side'], 'Kjøper fengsel', $pdo);
            if (max_eiendeler($_SESSION['ID'], $pdo)) {
                user_log($_SESSION['ID'], $_GET['side'], 'Har allerede 2 forskjellige eiendeler', $pdo);
                echo feedback("Du har allerede 2 forskjellige eiendeler", "fail");
            } elseif (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= $price_for_jail) {
                if (!fengsel_direktor_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                    take_money($_SESSION['ID'], $price_for_jail, $pdo);

                    $sql = "INSERT INTO fengsel_direktor (FENGDI_acc_id, FENGDI_city) VALUES (?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                    user_log($_SESSION['ID'], $_GET['side'], 'Kjøper fengsel vellykket', $pdo);
                    echo feedback("Du er nå ny fengselsdirektør i " . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)), "success");
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], 'Byen har allerede fengselsdirektør', $pdo);
                    echo feedback("Denne byen har allerede en fengselsdirektør", "error");
                }
            } else {
                user_log($_SESSION['ID'], $_GET['side'], 'Ikke nok penger til å kjøpe fengselet', $pdo);
                echo feedback("Du har ikke nok penger for å kjøpe fengselet", "error");
            }
        }
    }

    $point_price = 15;
    $money_price = (price_buyout_jail($_SESSION['ID'], $pdo) - time()) * getPrisonBuyoutPrice();

    if (isset($_POST['buy_out_points'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Kjøper seg ut med poeng', $pdo);
        if (player_in_jail($_SESSION['ID'], $pdo)) {
            if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) < $point_price) {
                user_log($_SESSION['ID'], $_GET['side'], 'Ikke nok poeng', $pdo);
                header("Location: ?side=fengsel&buyouterror");
            } else {
                take_poeng($_SESSION['ID'], $point_price, $pdo);

                $sql = "DELETE FROM fengsel WHERE FENG_acc_id = " . $_SESSION['ID'] . "";
                $pdo->exec($sql);

                user_log($_SESSION['ID'], $_GET['side'], 'Kjøper seg ut med poeng vellykket', $pdo);
                header("Location: ?side=fengsel&buyoutsuccess");
            }
        } else {
            user_log($_SESSION['ID'], $_GET['side'], 'Ikke lenger i fengsel', $pdo);
            echo feedback("Du er ikke lengre i fengsel", "error");
        }
    }

    if (isset($_POST['buy_out_money'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Kjøper seg ut av fengsel', $pdo);
        if (player_in_jail($_SESSION['ID'], $pdo)) {
            if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) < $money_price) {
                user_log($_SESSION['ID'], $_GET['side'], 'Ikke nok penger', $pdo);
                header("Location: ?side=fengsel&buyouterror");
            } else {
                take_money($_SESSION['ID'], $money_price, $pdo);

                if (fengsel_direktor_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) {
                    $sql = "UPDATE fengsel_direktor SET FENGDI_bank = FENGDI_bank + ? WHERE FENGDI_city = ? ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$money_price, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);
                }

                update_bedrift_inntekt($_SESSION['ID'], 2, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $money_price, $pdo);

                $sql = "DELETE FROM fengsel WHERE FENG_acc_id = " . $_SESSION['ID'] . "";
                $pdo->exec($sql);

                user_log($_SESSION['ID'], $_GET['side'], 'Nok penger = ute av fengsel', $pdo);
                header("Location: ?side=fengsel&buyoutsuccess");
            }
        } else {
            echo feedback("Du er ikke lengre i fengsel", "error");
        }
    }

    if (player_in_jail($_SESSION['ID'], $pdo)) {

?>
        <div class="col-7 single">
            <div class="content">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                <form method="post" id="myForm">
                    <?php

                    echo feedback("Du er i fengsel og kan ikke gjøre handlinger før du er ute av fengsel!", "error");

                    $time_left = price_buyout_jail($_SESSION['ID'], $pdo) - time();

                    $minutes = floor($time_left / 60);
                    $seconds = $time_left - $minutes * 60;

                    ?>
                    <script>
                        timeleft(<?php echo $time_left; ?>, "countdowntimer_myself");
                    </script>


                    <p style="padding-bottom: 0;">
                        <center>Du kan kjøpe deg ut av fengsel eller vente til noen bryter deg ut av fengsel
                            <br><br>
                            <input type="submit" id="buyout" name="buy_out_money" value="Kjøp meg ut for <?php echo number($money_price); ?>kr">
                            <input type="submit" name="buy_out_points" value="Kjøp meg ut for 15 poeng">
                        </center>

                    </p>
                </form>

                <div id="get_data_injail">
                </div>
                <script type="text/javascript">
                    function numberWithSpaces(x) {
                        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                    }

                    var timeleft = <?php echo $money_price ?>;
                    var downloadTimer = setInterval(function() {
                        timeleft = timeleft - getPrisonBuyoutPrice();
                        document.getElementById("buyout").setAttribute('value', 'Kjøp meg ut for ' + numberWithSpaces(timeleft) + 'kr');
                        if (timeleft <= 0)
                            clearInterval(downloadTimer);
                    }, 1000);

                    loadTableData_injail();

                    var interval = 1000; // hvert sekund
                    function loadTableData_injail() {
                        $.ajax({
                            url: "app/fengsel/load_inmates_injail.php",
                            type: "POST",
                            success: function(data) {
                                $("#get_data_injail").html(data);
                            },
                            complete: function(data) {
                                setTimeout(loadTableData_injail, interval);
                            }
                        });
                    }
                    setTimeout(loadTableData_injail, interval);
                </script>
            </div>
        </div>
    <?php

    } else {

    ?>
        <div class="col-7 single">
            <div class="content">
                <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
                <?php

                if (firma_on_marked(2, AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>
                    <p class="description" style="margin: 0px;">Fengselet i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?> ligger ute på <a href="?side=marked&id=5">marked</a></p>
                <?php } elseif (!fengsel_direktor_exist(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)) { ?>
                    <form method="post">
                        <p class="description">Det er ingen som er fengselsdirektør i <?php echo city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)); ?><br></p>
                        <input type="submit" name="buy_jail" value="Kjøp fengsel for <?php echo number($price_for_jail); ?>">
                    </form>
                    <?php

                } else {
                    echo '<center>';
                    echo 'Fengselsdirektør i ' . city_name(AS_session_row($_SESSION['ID'], 'AS_city', $pdo)) . ' er ';
                    echo ACC_username(get_fengselsdirektor(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo), $pdo);
                    echo '</center>';

                    if (get_fengselsdirektor(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo) == $_SESSION['ID']) {

                        if (isset($_POST['money_out'])) {
                            user_log($_SESSION['ID'], $_GET['side'], 'Tar ut penger fra firmaet', $pdo);
                            $money_in_bank = get_fengselsbank(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo);

                            if ($money_in_bank > 0) {

                                $sql = "UPDATE fengsel_direktor SET FENGDI_bank = ? WHERE FENGDI_city = ? ";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([0, AS_session_row($_SESSION['ID'], 'AS_city', $pdo)]);

                                give_money(get_fengselsdirektor(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo), $money_in_bank, $pdo);

                                user_log($_SESSION['ID'], $_GET['side'], 'Tar ut ' . number($money_in_bank) . ' kr fra firmaet', $pdo);

                                header("Location: ?side=fengsel&penger_ut=$money_in_bank");
                            } else {
                                header("Location: ?side=fengsel&penger_ut=$money_in_bank");
                            }
                        }

                        if (isset($_GET['penger_ut'])) {
                            if ($_GET['penger_ut'] > 0) {
                                echo feedback("Du tok ut " . number($_GET['penger_ut']) . " kr fra fengselsbanken", "success");
                            } else {
                                echo feedback("Du må ha minst 1 krone i banken for å ta ut penger", "error");
                            }
                        }

                    ?>
                        <center>
                            <form method="post">
                                <input type="submit" onclick="return confirm('Er du sikker på at du ønsker å selge fengselet for <?php echo number($price_for_sell); ?>?')" name="sell_jail" value="Selg fengselet til staten for <?php echo number($price_for_sell); ?> kr">


                                <p>Penger i fengselsbanken: <?php echo number(get_fengselsbank(AS_session_row($_SESSION['ID'], 'AS_city', $pdo), $pdo)); ?> kr</p>
                                <input type="submit" name="money_out" value="Ta ut alle penger fra fengselet">
                            </form>
                        </center>
                <?php
                    }
                }

                ?>
                <div id="get_data">
                </div>
                <script type="text/javascript">
                    loadTableData();

                    var interval = 1000; // hvert sekund
                    function loadTableData() {
                        $.ajax({
                            url: "app/fengsel/load_inmates.php",
                            type: "POST",
                            success: function(data) {
                                $("#get_data").html(data);
                            },
                            complete: function(data) {
                                setTimeout(loadTableData, interval);
                            }
                        });
                    }
                    setTimeout(loadTableData, interval);
                </script>
            </div>
        </div>
        <div class="col-7 single" style="margin-top: 10px;">
            <div class="content">
                <h4>Topp 10 utbrytere</h4>
                <table>
                    <tr>
                        <th style="width: 10px;">#</th>
                        <th>Bruker</th>
                        <th>Antall utbrytninger</th>
                    </tr>

                    <?php

                    $i = 0;

                    $query = $pdo->prepare('
                SELECT 
                    user_statistics.US_acc_id, (user_statistics.US_jail + 0) as US_jail
                FROM 
                    user_statistics
                INNER JOIN 
                    accounts
                ON 
                    user_statistics.US_acc_id = accounts.ACC_id
                WHERE NOT
                    accounts.ACC_type IN (5)
                ORDER BY 
                US_jail DESC
                LIMIT 10');
                    $query->execute();
                    foreach ($query as $row) {
                        $i++;
                    ?>
                        <tr>
                            <td><?php echo $i; ?>.</td>
                            <td><?php echo ACC_username($row['US_acc_id'], $pdo); ?></td>
                            <td><?php echo number($row['US_jail']); ?> stk</td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
<?php }
} ?>