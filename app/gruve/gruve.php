<?php

if (AS_session_row($_SESSION['ID'], 'AS_city', $pdo) == 5) {
    $action[0] = "Let etter diamanter i bekken";
    $action[1] = "Hakk etter diamanter ved inngangen";
    $action[2] = "Let etter diamanter inne i gruven";
    $action[3] = "Finn diamanter dypest i gruven";

    $cooldown_event[0] = 10;
    $cooldown_event[1] = 25;
    $cooldown_event[2] = 55;
    $cooldown_event[3] = 85;

    $expToPlayer[0] = 10;
    $expToPlayer[1] = 18;
    $expToPlayer[2] = 30;
    $expToPlayer[3] = 50;

    $payout_from[0] = 1;
    $payout_to[0] = 2;
    $payout_from[1] = 2;
    $payout_to[1] = 5;
    $payout_from[2] = 5;
    $payout_to[2] = 10;
    $payout_from[3] = 10;
    $payout_to[3] = 50;

    $payout[0] = mt_rand($payout_from[0], $payout_to[0]);
    $payout[1] = mt_rand($payout_from[1], $payout_to[1]);
    $payout[2] = mt_rand($payout_from[2], $payout_to[2]);
    $payout[3] = mt_rand($payout_from[3], $payout_to[3]);

    $risk_addon[0] = 2;
    $risk_addon[1] = 5;
    $risk_addon[2] = 10;
    $risk_addon[3] = 20;

    if (isset($_GET['dia'])) {
        $str = 'diamanter';
        if ($_GET['dia'] === 1) $str = 'diamant';

        echo feedback('Vellykket! Du fant ' . $_GET['dia'] . ' ' . $str, 'success');
    }

    if (isset($_GET['smisk'])) {
        echo feedback('Du smisket med politinivået og risikonivået er nå lavere', 'success');
    }

    if (isset($_GET['alt'])) {
        $alt = $_GET['alt'];
        $payoutDiamonds = $payout[$alt];
        $newRiskNiva = DE_session_row($_SESSION['ID'], 'DE_risk_level', $pdo) + $risk_addon[$alt];

        if (DE_session_row($_SESSION['ID'], 'DE_ban', $pdo) >= time()) {
            echo feedback('Du er bannlyst fra Abu Dhabi!', 'error');
        } elseif (event_ready($_SESSION['ID'], $pdo)) {
            echo feedback('Du har ventetid!', 'error');
        } elseif (is_numeric($alt) && $alt >= 0 && $alt <= count($expToPlayer)) {
            event_give_cooldown($_SESSION['ID'], $cooldown_event[$alt] + time(), $pdo);

            $sql = "UPDATE diamond_event SET DE_risk_level = $newRiskNiva WHERE DE_acc_id='" . $_SESSION['ID'] . "'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            if ($newRiskNiva >= 100) {
                $new_city = mt_rand(0, 4);
                change_city($_SESSION['ID'], $new_city, $pdo);

                $sql = "UPDATE diamond_event SET DE_ban = ?, DE_risk_level = ? WHERE DE_acc_id = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([time() + 3600, 0,  $_SESSION['ID']]);

                header("Location: ?side=flyplass&tooHighRisk=" . $new_city);
            }

            give_exp($_SESSION['ID'], $expToPlayer[$alt], $pdo);

            check_rankup($_SESSION['ID'], AS_session_row($_SESSION['ID'], 'AS_rank', $pdo), AS_session_row($_SESSION['ID'], 'AS_exp', $pdo), $pdo);

            for ($i = 0; $i < $payoutDiamonds; $i++) {
                $thing_id = 39;
                update_things($_SESSION['ID'], $thing_id, $pdo);
            }

            header("Location: ?side=gruve&dia=" . $payoutDiamonds);
        } else {
            echo feedback('Ugyldig ID', 'error');
        }
    }

    $price_points = 10;
    $remove_risk = 10;

    if (isset($_POST['smisk'])) {
        if (DE_session_row($_SESSION['ID'], 'DE_risk_level', $pdo) === 0) {
            echo feedback('Du har ingen risikonivå', 'fail');
        } else {
            if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $price_points) {

                if (DE_session_row($_SESSION['ID'], 'DE_risk_level', $pdo) > $remove_risk) {
                    $sql = "UPDATE diamond_event SET DE_risk_level = DE_risk_level - ? WHERE DE_acc_id = ? ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$remove_risk,  $_SESSION['ID']]);
                } else {
                    $sql = "UPDATE diamond_event SET DE_risk_level = ? WHERE DE_acc_id = ? ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([0,  $_SESSION['ID']]);
                }

                take_poeng($_SESSION['ID'], $price_points, $pdo);

                header("Location: ?side=gruve&smisk");
            } else {
                echo feedback('Du har ikke nok poeng', 'error');
            }
        }
    }

?>

    <style>
        progress {
            border-radius: 7px;
            width: 50%;
            margin: 0 auto;
            border-radius: var(--border-radius);
        }

        progress::-webkit-progress-bar {
            background-color: var(--main-bg-color);
            border-radius: var(--border-radius);
        }

        progress::-webkit-progress-value {
            background-color: var(--not-ready-color);
            border-radius: var(--border-radius);
        }
    </style>

    <div class="col-8 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/diamantgruve.png">
            <p class="description">
                I gruven kan du grave etter diamanter. Hvis du går for en mer risikabel måte å grave etter diamanter på vil også risikinivået øke.
                Dersom risikonivået ditt øker til 100% vil du bli kastet ut av Abu Dhabi i 1 time. Hvert minutt vil risikonivået gå ned 2%. Siden Abu Dhabi er korrupt har du mulighet
                for å smiske med politiet for å senke politinivået med <?= $remove_risk ?>% for <?= $price_points ?> poeng.
            </p>
            <div style="display: flex; flex-direction: column; justify-content: center; text-align: center; margin: 25px 0px;">
                <div style="display: flex; justify-content: center; align-items: center;">
                    <p class="description">Risikonivå <?php echo DE_session_row($_SESSION['ID'], 'DE_risk_level', $pdo); ?>%</p>

                    <form method="post">
                        <input type="submit" name="smisk" style="margin-left: 10px; padding: 5px 10px;" value="Smisk med politiet (<?= $price_points ?> poeng)" />
                    </form>
                </div>
                <progress value="<?php echo DE_session_row($_SESSION['ID'], 'DE_risk_level', $pdo); ?>" max="100" />
            </div>

            <?php

            if (event_ready($_SESSION['ID'], $pdo)) {
                $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                $stmt->execute(array(
                    ':cd_id' => $_SESSION['ID']
                ));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $time_left = $row['CD_event'] - time();

                $minutes = floor($time_left / 60);
                $seconds = $time_left - $minutes * 60;

                echo feedback('Du har nylig utført en graving. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan utføre ny graving.', 'cooldown'); ?>
                <script>
                    timeleft(<?php echo $time_left; ?>, "countdowntimer");
                </script>

            <?php

            } else {

            ?>
                <table>
                    <tr>
                        <th style="width: 40%;">Handling</th>
                        <th style="width: 15%;">Risiko</th>
                        <th style="width: 15%;">Ventetid</th>
                        <th style="width: 30%;">Utbetaling</th>
                    </tr>
                    <?php for ($i = 0; $i < count($cooldown_event); $i++) { ?>
                        <tr class="cursor_hover clickable-row" data-href="?side=gruve&alt=<?php echo $i; ?>">
                            <td><?= $action[$i]; ?></td>
                            <td style="color: var(--not-ready-color);">+<?= $risk_addon[$i]; ?></td>
                            <td><?= $cooldown_event[$i]; ?>s</td>
                            <td><?= $payout_from[$i] . ' - ' . $payout_to[$i]; ?> diamanter og <?php echo $expToPlayer[$i] ?> EXP</td>
                        </tr>
                    <?php } ?>
                </table>

            <?php } ?>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                $(".clickable-row").unbind("click"); //Fjern click-event-listener for alle .clickable-row's
                window.location = $(this).data("href");
            });
        });
    </script>
<?php } ?>