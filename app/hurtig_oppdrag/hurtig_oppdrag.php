<?php

if (player_in_bunker($_SESSION['ID'], $pdo)) {
    include 'app/bunker/bunker.php';
} elseif (player_in_jail($_SESSION['ID'], $pdo)) {
    header("Location: ?side=fengsel");
} else {

    $query = $pdo->prepare("SELECT * FROM hurtig_oppdrag ORDER BY HO_date DESC LIMIT 1");
    $query->execute();
    $active_HO = $query->fetch(PDO::FETCH_ASSOC);
    $amount_of_things = false;
    $amount_of_cars = false;
    $active = false;

    $cars = 0;
    $things = 0;

    for ($i = 0; $i < 5; $i++) {
        $cars = $cars + amount_of_car($_SESSION['ID'], $i, $active_HO['HO_car_id'], $pdo);
    }

    if ($cars && $cars >= $active_HO['HO_car_amount']) {
        $amount_of_cars = true;
    } else {
        $amount_of_cars = false;
    }

    $things = $things + amount_of_things($_SESSION['ID'], $active_HO['HO_thing_id'], $pdo);

    if ($things && $things >= $active_HO['HO_thing_amount']) {
        $amount_of_things = true;
    } else {
        $amount_of_cars = false;
    }

    if (isset($_POST['submit'])) {
        if ($active_HO['HO_car_id'] > 0 && $active_HO['HO_car_id'] <= 5) {
            $money_pr_car = 2000000;
            $exp_pr_car = 1;
        } elseif ($active_HO['HO_car_id'] >= 6 && $active_HO['HO_car_id'] <= 11) {
            $money_pr_car = 5000000;
            $exp_pr_car = 2;
        } else {
            $money_pr_car = 10000000;
            $exp_pr_car = 4;
        }

        if ($active_HO['HO_thing_id'] > 0 && $active_HO['HO_thing_id'] <= 9) {
            $money_pr_thing = 2000000;
            $exp_pr_thing = 1;
        } elseif ($active_HO['HO_thing_id'] >= 10 && $active_HO['HO_thing_id'] <= 19) {
            $money_pr_thing = 5000000;
            $exp_pr_thing = 2;
        } else {
            $money_pr_thing = 10000000;
            $exp_pr_thing = 4;
        }

        if ($active_HO['HO_status'] == 0) {
            $active = true;
        }

        if ($active) {
            if ($amount_of_things && $amount_of_cars) {
                $money_to_user = (($money_pr_car * $active_HO['HO_car_amount']) + ($money_pr_thing * $active_HO['HO_thing_amount']));
                $exp_to_user = (($exp_pr_car * $active_HO['HO_car_amount']) + ($exp_pr_thing * $active_HO['HO_thing_amount']));

                for ($p = 0; $p < $active_HO['HO_car_amount']; $p++) {
                    $sql = "DELETE FROM garage WHERE GA_car_id = " . $active_HO['HO_car_id'] . " AND GA_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                    $pdo->exec($sql);
                }

                for ($t = 0; $t < $active_HO['HO_thing_amount']; $t++) {
                    $sql = "DELETE FROM things WHERE TH_type = " . $active_HO['HO_thing_id'] . " AND TH_acc_id = " . $_SESSION['ID'] . " LIMIT 1";
                    $pdo->exec($sql);
                }

                give_money($_SESSION['ID'], $money_to_user, $pdo);
                give_exp($_SESSION['ID'], $exp_to_user, $pdo);
                check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

                if (active_energy_drink($id, $pdo)) {
                    $exp_to_user = $exp_to_user * 2;
                }

                if (active_superhelg($pdo)) {
                    $exp_to_user = $exp_to_user * 2;
                }

                $sql = "UPDATE hurtig_oppdrag SET HO_status = ? WHERE HO_id = ?";
                $pdo->prepare($sql)->execute([$_SESSION['ID'], $active_HO['HO_id']]);

                $one = 1;
                $sql = "UPDATE user_statistics SET US_hurtig_oppdrag = US_hurtig_oppdrag + ? WHERE US_acc_id = ?";
                $pdo->prepare($sql)->execute([$one, $_SESSION['ID']]);

                if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 45 || AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 47) {
                    mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
                }

                header("Location: ?side=hurtig_oppdrag&vel=" . $money_to_user . "&exp=" . $exp_to_user . "");
            } else {
                echo feedback("Du har ikke nok utstyr til å utføre det hurtige oppdraget", "error");
            }
        } else {
            echo feedback("Du var for sen. Det hurtige oppdraget er allerede utført av en annen spiller.", "error");
        }
    }

    if (isset($_GET['vel']) && is_numeric($_GET['vel'])) {
        echo feedback("Du har klart det hurtige oppdraget og får " . number($_GET['vel']) . " kr og " . number($_GET['exp']) . " exp!", "success");
    }

?>

    <div class="col-8 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <p class="description">Hver time vil et nytt hurtig oppdrag komme, det er første mann til mølla prinsippet som gjelder, den første som oppnår kravene og utfører hurtig oppdrag vil få en belønning.</p>
            <?php

            if ($active_HO && $active_HO['HO_status'] != 0) {
                echo feedback('Oppdraget for denne timen er allerede utført. Kom tilbake ved neste timeskifte for nytt oppdrag', "error");
            } else { ?>
                <center>

                    <p class="description">Oppdrag:

                        <?php

                        if ($active_HO) {
                            echo 'skaff <b style="color: var(--button-bg-color);">' . $active_HO['HO_car_amount'] . ' stk ' . car($active_HO['HO_car_id']) . '</b> og <b style="color: var(--button-bg-color);">' . $active_HO['HO_thing_amount'] . ' stk ' . thing($active_HO['HO_thing_id']) . '</b>';
                        }

                        ?>
                    </p>
                    <p class="description">
                        Du har:
                        <?php if ($active_HO) { ?>
                            <b style="color: var(--button-bg-color);"><?php echo $cars; ?> av <?php echo '' . $active_HO['HO_car_amount'] . ' ' . car($active_HO['HO_car_id']) . ''; ?></b> og
                            <b style="color: var(--button-bg-color);"><?php echo $things; ?> av <?php echo '' . $active_HO['HO_thing_amount'] . ' ' . thing($active_HO['HO_thing_id']) . ''; ?></b>
                        <?php } ?>
                    </p>
                    <form method="post">
                        <input type="submit" style="margin-top: 10px;" name="submit" value="Utfør hurtig oppdrag">
                    </form>
                </center>
            <?php } ?>
        </div>
    </div>
    <div class="col-8 single" style="margin-top: 10px;">
        <div class="content">
            <h4>Topp 10 flest utført hurtige oppdrag</h4>
            <table>
                <tr>
                    <th style="width: 10px;">#</th>
                    <th>Bruker</th>
                    <th>Antall hurtige oppdrag</th>
                </tr>

                <?php

                $i = 0;

                $query = $pdo->prepare('
                SELECT 
                    user_statistics.US_acc_id, (user_statistics.US_hurtig_oppdrag + 0) as US_hurtig_oppdrag
                FROM 
                    user_statistics
                INNER JOIN 
                    accounts
                ON 
                    user_statistics.US_acc_id = accounts.ACC_id
                WHERE NOT
                    accounts.ACC_type IN (5)
                ORDER BY 
                    US_hurtig_oppdrag DESC
                LIMIT 10
                ');
                $query->execute();
                foreach ($query as $row) {
                    $i++;
                ?>
                    <tr>
                        <td><?php echo $i; ?>.</td>
                        <td><?php echo ACC_username($row['US_acc_id'], $pdo); ?></td>
                        <td><?php echo number($row['US_hurtig_oppdrag']); ?> stk</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
<?php } ?>