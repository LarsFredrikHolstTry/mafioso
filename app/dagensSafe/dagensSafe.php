<?php

$stmt = $pdo->prepare("SELECT * FROM safe_number");
$stmt->execute();
$row = $stmt->fetch();

$number = $row['SAFE_number'];
$hasWinner = $row['SAFE_winner'] != 0;
$cooldown = (60 * 5) + time();

$moneyWin =         mt_rand(10000000, 100000000);
$kuler =            mt_rand(10, 50);
$hemmeligKiste =    mt_rand(0, 1);
$exp =              mt_rand(5, 50);

$winningStringOriginal = '';
$winningString = number($moneyWin) . " kr, " . number($exp) . " exp, " . number($kuler) . " kuler";
$winningStringWithoutHemmeligKiste = number($moneyWin) . "kr, " . number($exp) . " exp og " . number($kuler) . " kuler";

if ($hemmeligKiste == 1) {
    $winningStringOriginal = $winningString . ' og en hemmelig kiste!';
} else {
    $winningStringOriginal = $winningStringWithoutHemmeligKiste;
}


if (isset($_POST['openSafe'])) {
    if (isset($_POST['code'])) {
        $code = $_POST['code'];

        if (safe_ready($_SESSION['ID'], $pdo)) {
            echo feedback("Ventetid!", "fail");
        } elseif ($hasWinner) {
            echo feedback("Koden er allerede knekket av en annen spiller!", "fail");
        } else {
            if (!is_numeric($code) || strlen($code) > 4 || strlen($code) < 4) {
                echo feedback("Koden består kun av 4 tall", "fail");
            } else {
                if ($code == $number) {
                    $sql = "UPDATE safe_number SET SAFE_winner = ?, SAFE_prize = ? WHERE SAFE_number = ? ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['ID'], $winningStringOriginal, $number]);

                    if ($hemmeligKiste) {
                        update_things($_SESSION['ID'], 30, $pdo);
                    }

                    give_money($_SESSION['ID'], $moneyWin, $pdo);
                    give_bullets($_SESSION['ID'], $kuler, $pdo);
                    give_exp($_SESSION['ID'], $exp, $pdo);

                    echo feedback("Riktig kode! I safen fant du " . $winningStringOriginal, "success");
                } elseif ($code < $number) {
                    echo feedback("Feil kode! Prøv et høyere tall neste gang", "fail");
                } elseif ($code > $number) {
                    echo feedback("Feil kode! Prøv et lavere tall neste gang", "fail");
                }

                if ($code != $number) {
                    safe_give_cooldown($_SESSION['ID'], $cooldown, $pdo);
                }

                $sql = "INSERT INTO safe_attempts (SAT_acc_id, SAT_number, SAT_date)
                VALUES ('" . $_SESSION['ID'] . "', '" . $code . "', " . time() . ")";
                $pdo->exec($sql);
            }
        }
    }
}

?>
<div class="col-12 single">
    <div class="col-7">
        <div class="content">
            <img class="action_image" src="img/action/actions/dagensSafe.png" />
            <p class="description">
                Dagens safe kan åpnes en gang om dagen og inneholder en stor belønnelse!
                Koden består av en 4-sifret kode bestående kun av tall. For at du ikke
                skal legge fra deg for mye oppmerksomhet kan du kun prøve koden hvert 5. minutt.
                Klokken 00:00 blir koden endret og forsøkene samt vinneren blir nullstilt.
            </p>
            <?php

            if (safe_ready($_SESSION['ID'], $pdo)) {
                $stmt = $pdo->prepare('SELECT * FROM cooldown WHERE CD_acc_id = :cd_id');
                $stmt->execute(array(
                    ':cd_id' => $_SESSION['ID']
                ));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $time_left = $row['CD_safe'] - time();

                $minutes = floor($time_left / 60);
                $seconds = $time_left - $minutes * 60;

                echo feedback('Du har nylig prøvd deg på å åpne safen. Du må vente <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før du kan prøve igjen.', 'cooldown'); ?>
                <script>
                    timeleft(<?php echo $time_left; ?>, "countdowntimer");
                </script>

            <?php

            } else {

            ?>
                <?php if (!$hasWinner) { ?>
                    <form method="post">
                        <div style="display: flex; justify-content:center; align-items: center">
                            <div style="margin-right: 10px;">
                                <input type="text" name="code" placeholder="****" style="width: 70px;" />
                            </div>
                            <input type="submit" name="openSafe" value="Åpne safen" />
                        </div>
                    </form>
                <?php
                } else {
                    echo feedback('Koden var ' . $number . ' og ' . ACC_username($row['SAFE_winner'], $pdo) . ' var den heldige vinneren av ' . $row['SAFE_prize'], 'success');
                }
                ?>
            <?php } ?>
        </div>
    </div>
    <div class="col-5">
        <div class="content">
            <h4>20 siste forsøk</h4>
            <p class="description">
                Under kan du se de 20 siste gjetteforsøkene for i dag og om disse var for høye eller for lave
            </p>
            <table>
                <tr>
                    <th>Forsøk</th>
                    <th>Status</th>
                    <th>Brukernavn</th>
                    <th>Dato</th>
                </tr>
                <?php
                $sql = "SELECT * FROM safe_attempts ORDER BY SAT_date DESC LIMIT 20";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?= $row['SAT_number'] ?></td>
                        <td><?php
                            if ($row['SAT_number'] == $number) {
                                echo '<span style="color: var(--ready-color);">Riktig!</span>';
                            } elseif ($row['SAT_number'] < $number) {
                                echo 'For lavt';
                            } else {
                                echo 'For høyt';
                            }
                            ?></td>
                        <td><?= ACC_username($row['SAT_acc_id'], $pdo) ?></td>
                        <td><?= date_to_text($row['SAT_date']) ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>