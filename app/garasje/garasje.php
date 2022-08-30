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
            echo feedback("Du solgte biler for " . number($_GET['selg_alle']) . " kr", "success");
        }
    }

    $total_cars = 18;

    if (isset($_POST['sell_chosen'])) {
        user_log($_SESSION['ID'], $_GET['side'], 'Selger valgte biler', $pdo);

        $money = 0;
        $amount_of_cars = 0;
        $cars_sold = false;


        foreach ($_POST['values'] as $data) {
            if ($data['amount'] == 0) {
            } else {
                if ($data['amount'] > amount_of_car($_SESSION['ID'], $data['city_id'], $data['car_id'], $pdo)) {
                    user_log($_SESSION['ID'], $_GET['side'], 'Ugyldig antall biler', $pdo);
                    echo feedback("Ugyldig antall biler", "fail");
                    $cars_sold = false;
                    break;
                } else {
                    for ($j = 0; $j < $data['amount']; $j++) {
                        $money = $money + car_price($data['car_id']);
                        $amount_of_cars++;

                        $sql = "DELETE FROM garage WHERE GA_acc_id = " . $_SESSION['ID'] . " AND GA_car_id = " . $data['car_id'] . " AND GA_car_city = " . $data['city_id'] . " LIMIT 1";
                        $pdo->exec($sql);
                    }
                    $cars_sold = true;
                }
            }
        }

        if ($cars_sold) {
            $money_to_player = $money;

            if (active_superhelg($pdo)) {
                $money_to_player = $money_to_player * 2;
            }

            give_money($_SESSION['ID'], $money_to_player, $pdo);

            if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 38) {
                mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + $money_to_player, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
            }

            if ($amount_of_cars == 1) {
                user_log($_SESSION['ID'], $_GET['side'], "solgte " . $amount_of_cars . " bil for " . number($money_to_player) . "", $pdo);
                echo feedback("Du solgte " . $amount_of_cars . " bil for " . number($money_to_player) . " kr", "success");
            } else {

                user_log($_SESSION['ID'], $_GET['side'], "solgte " . $amount_of_cars . " biler for " . number($money_to_player) . "", $pdo);
                echo feedback("Du solgte " . $amount_of_cars . " biler for " . number($money_to_player) . " kr", "success");
            }
        }
    }

    if (isset($_POST['sell_all'])) {
        user_log($_SESSION['ID'], $_GET['side'], "Selger alle biler", $pdo);

        if ($cars_in_garage == 0) {
            echo feedback("Du har ingen biler å selge", "error");
        } else {
            $money_amount = value_all_cars($_SESSION['ID'], $pdo);

            $money_amount_give = $money_amount;

            if (active_superhelg($pdo)) {
                $money_amount_give = $money_amount_give * 2;
            }

            give_money($_SESSION['ID'], $money_amount_give, $pdo);

            if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 38) {
                mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + $money_amount_give, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
            }

            $sql = "DELETE FROM garage WHERE GA_acc_id = " . $_SESSION['ID'] . "";
            $pdo->exec($sql);

            user_log($_SESSION['ID'], $_GET['side'], "Selger alle for: " . $money_amount_give . " kr", $pdo);
            header("Location: ?side=garasje&selg_alle=" . $money_amount_give . "");
        }
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
                <p class="description">Her vil du se dine biler. Disse kan selges for prisen oppgitt eller brukes i oppdrag.</p>
                <form method="post" class="table_check">
                    <table id="datatable" data-order="[[3]]" data-paging="false">
                        <thead>
                        <tr>
                            <th style="width: 30%;">Bil</th>
                            <th style="width: 10%;">Antall</th>
                            <th style="width: 15%;">By</th>
                            <th style="width: 15%;">Verdi</th>
                            <th style="width: 5%;">Antall</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $i = 0;

                        $sql = "SELECT DISTINCT GA_car_id, GA_car_city FROM garage WHERE GA_acc_id = " . $_SESSION['ID'] . " ORDER BY GA_car_id DESC";
                        $stmt = $pdo->query($sql);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $amount_of_car = amount_of_car($_SESSION['ID'], $row['GA_car_city'], $row['GA_car_id'], $pdo);
                        ?>
                            <tr>
                                <td><?php echo car($row['GA_car_id']); ?></td>
                                <td data-order="<?php echo $amount_of_car ?>"><?php echo $amount_of_car ?> stk</td>
                                <td><?php echo country_flags($row['GA_car_city']) . " ";
                                    echo city_name($row['GA_car_city']); ?></td>
                                <td data-order="<?php echo car_price($row['GA_car_id']) ?>"><?php echo number(car_price($row['GA_car_id'])); ?> kr</td>
                                <td>
                                    <input type="hidden" style="margin: 0; padding: 0;" name="values[<?php echo $i; ?>][car_id]" value="<?php echo $row['GA_car_id']; ?>">
                                    <input type="hidden" style="margin: 0; padding: 0;" name="values[<?php echo $i; ?>][city_id]" value="<?php echo $row['GA_car_city']; ?>">
                                    <input type="number" style="width: 50px; margin: 0;" name="values[<?php echo $i; ?>][amount]" placeholder="0" min="0">
                                </td>
                            </tr>
                        <?php
                            $i++;
                        } ?>
                        </tbody>
                    </table>
                    <div>
                        <p style="float: left;" class="description"><a href="?side=poeng"><?php echo $cars_in_garage; ?> av <?php echo $max_garage; ?> plasser brukt </a> <b><a href="?side=poeng">Oppgrader</a></b></p>
                        <p style="float: right;" class="description">Total verdi:
                            <?php

                            $value = value_all_cars($_SESSION['ID'], $pdo) * 0.9;

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

                    if (!garage_car_exist($_SESSION['ID'], $pdo)) {
                        echo feedback("Du har ingen biler. Gjør <a href='?side=biltyveri'>biltyeri</a> for å skaffe biler du kan selge", "blue");
                    } else {
                    ?>

                        <input type="submit" name="sell_all" value="Selg alle" onclick="return confirm('Er du sikker på at du ønsker å selge alle bilene?');">
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
            $beskyttelse[3] = "Nedgravet garasje";
            $beskyttelse[4] = "Livvakter";
            $beskyttelse[5] = "Premium livvakt";

            $beskyttelse_pris[0] = 0;
            $beskyttelse_pris[1] = 10000000;
            $beskyttelse_pris[2] = 50000000;
            $beskyttelse_pris[3] = 100000000;
            $beskyttelse_pris[4] = 250000000;
            $beskyttelse_pris[5] = 100;

            $stmt = $pdo->prepare("SELECT BESK_garasje FROM beskyttelse WHERE BESK_acc_id = :id");
            $stmt->execute(['id' => $_SESSION['ID']]);
            $row_garasje = $stmt->fetch();

            $legal = array(0, 1, 2, 3, 4, 5);

            if (isset($_GET['upgraded'])) {
                if (in_array($_GET['upgraded'], $legal) && is_numeric($_GET['upgraded'])) {
                    echo feedback("Du kjøpte " . $beskyttelse[$_GET['upgraded']], "success");
                } else {
                    echo feedback("Feilmelding: 1523", "error");
                }
            }

            if (isset($_GET['upgrade'])) {
                user_log($_SESSION['ID'], $_GET['side'], "Oppgradere beskyttelse", $pdo);
                $upgrade_type = $_GET['upgrade'];

                if (in_array($_GET['upgrade'], $legal) && is_numeric($_GET['upgrade'])) {
                    if (!$row_garasje) {
                        $sql = "INSERT INTO beskyttelse (BESK_acc_id) VALUES (?)";
                        $pdo->prepare($sql)->execute([$_SESSION['ID']]);
                    }

                    if ($row_garasje && $upgrade_type <= $row_garasje['BESK_garasje']) {
                        echo feedback("Du har allerede " . $beskyttelse[$upgrade_type] . "", "fail");
                    } elseif ($upgrade_type - 1 != $row_garasje['BESK_garasje']) {
                        echo feedback("Du må ha " . $beskyttelse[$row_garasje['BESK_garasje'] + 1] . " før du kan oppgradere", "error");
                    } elseif (
                        $beskyttelse_pris[$upgrade_type] != 5 && $beskyttelse_pris[$upgrade_type] > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)
                        ||
                        $upgrade_type == 5 && $beskyttelse_pris[$upgrade_type] > AS_session_row($_SESSION['ID'], 'AS_points', $pdo)
                    ) {
                        echo feedback("Du har ikke nok penger / poeng til å kjøpe " . $beskyttelse[$upgrade_type], "error");
                    } else {
                        $sql = "UPDATE beskyttelse SET BESK_garasje = (BESK_garasje + 1) WHERE BESK_acc_id='" . $_SESSION['ID'] . "'";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        if ($upgrade_type != 5) {
                            take_money($_SESSION['ID'], $beskyttelse_pris[$upgrade_type], $pdo);
                        } else {
                            take_poeng($_SESSION['ID'], $beskyttelse_pris[$upgrade_type], $pdo);
                        }

                        user_log($_SESSION['ID'], $_GET['side'], "Oppgradert beskyttelse", $pdo);
                        header("location: ?side=garasje&upgraded=" . $upgrade_type . "");
                    }
                } else {
                    user_log($_SESSION['ID'], $_GET['side'], "Ulovlig type: " . $_GET['upgrade'], $pdo);
                    echo feedback("Feilmelding: 5233", "error");
                }
            }


            $activated = "img/icons/check-mark.svg";
            $not_activated = "img/icons/cancel.svg";

            ?>
            <div class="content">
                <h4>Beskyttelse</h4>
                <p class="description">Beskyttelse er viktig for å unngå at folk raner biler fra deg. Desto flere sikkerhetstiltak du gjør, desto vanskeligere er det å rane biler fra deg.</p>
                <p>Sjanse for å bryte seg inn i din garasje:

                    <?php

                    $total_beskyttelse = stjel_sjanse_beskyttelse($_SESSION['ID'], 'garasje', $pdo);

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
                        <tr class="cursor_hover clickable-row" data-href="?side=garasje&upgrade=<?php echo $i; ?>">
                            <td><img style="max-width: 21px;" src="
                            <?php if ($row_garasje && $row_garasje['BESK_garasje'] < $i) {
                                echo $not_activated;
                            } else {
                                echo $activated;
                            } ?>"></td>
                            <td><?php echo $beskyttelse[$i]; ?></td>
                            <td><?php
                                if ($i == 0) {
                                    echo 'gratis';
                                } else {
                                    echo number($beskyttelse_pris[$i]);
                                }

                                if ($i == 0) {
                                    echo '';
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
        number_space("#number");


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
