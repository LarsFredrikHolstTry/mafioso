<?php

$handlingName = ['Krev penger fra en restaurant', 'Krev penger fra en tatoveringsjappe', 'Krev penger fra den lokale Levis sjappa'];
$cooldown = [15, 35, 50];
$payoutFrom = [1000, 10000, 25000];
$payoutTo = [5000, 20000, 50000];
$chance[0] = 15 + $chanceBoost[$PENG_row['PENG_equipment']];
$chance[1] = 5 + $chanceBoost[$PENG_row['PENG_equipment']];
$chance[2] = $chanceBoost[$PENG_row['PENG_equipment']];
$jailTime = time() + 250;
$members = json_decode($PENG_row['PENG_members']);
$total_members = 1;
$cooldownFail = time() + 1800; // Halvtime cooldown
$logDesc = array('fra en restaurant', 'fra en tatoveringsjappe', 'fra den lokale Levis sjappa');

foreach ($members as $member) {
    $total_members = $total_members + 1;
}

if (isset($_GET['v'])) {
    echo feedback('Du skaffet ' . number($_GET['v']) . ' kr via pengeinnkreving', 'success');
}

if (isset($_GET['alt'])) {
    $alt = $_GET['alt'];
    if ($PENG_row['PENG_leader'] != $_SESSION['ID']) {
        echo feedback('Det er kun lederen av en gruppe som kan utføre pengeinnkreving!', 'fail');
    } elseif (event_ready($_SESSION['ID'], $pdo)) {
        echo feedback('Du har ventetid!', 'fail');
    } elseif (mt_rand(1, 100) > $chance[$alt]) {

        put_in_jail($PENG_row['PENG_leader'], $jailTime, 'Feilet en pengeinnkreving', $pdo);
        send_notification($PENG_row['PENG_leader'], "Pengeinnkrevingen er ferdig og dere fikk tak i " . number($PENG_row['PENG_money']) . " kr før politiet fersket dere!", $pdo);
        event_give_cooldown($PENG_row['PENG_leader'], $cooldownFail, $pdo);

        foreach ($members as $member) {
            put_in_jail($member, $jailTime, 'Feilet en pengeinnkreving', $pdo);
            send_notification($member, "Pengeinnkrevingen er ferdig og dere fikk tak i " . number($PENG_row['PENG_money']) . " kr før politiet fersket dere!", $pdo);
            event_give_cooldown($member, $cooldownFail, $pdo);
        }

        $sql = "DELETE FROM peng_group WHERE PENG_id = " . $PENG_row['PENG_id'] . "";
        $pdo->exec($sql);

        last_event($_SESSION['ID'], ' sin pengeinnkrevingsgruppe tjente ' . number($PENG_row['PENG_money']) . ' kr per medlem før gruppen ble oppløst.', $pdo);

        header("location: ?side=pengeinnkreving&m");
    } else {
        event_give_cooldown($_SESSION['ID'], time() + $cooldown[$alt], $pdo);
        $money_payout = mt_rand($payoutFrom[$alt], $payoutTo[$alt]);

        $sql = "UPDATE peng_group SET PENG_money = PENG_money + ? WHERE PENG_id = ?";
        $pdo->prepare($sql)->execute([($money_payout * $total_members), $pengId]);

        foreach ($members as $member) {
            give_money($member, $money_payout, $pdo);
        }

        $sql = "INSERT INTO peng_logg (PENGLOG_desc, PENGLOG_pengID, PENGLOG_money, PENGLOG_leader, PENGLOG_date) VALUES (?,?,?,?,?)";
        $pdo->prepare($sql)->execute(["Gruppen krevde <span style='color: var(--ready-color);'>" . number($money_payout) . "kr</span> " . $logDesc[$alt], $PENG_row['PENG_id'], $money_payout, $_SESSION['ID'], time()]);

        give_money($PENG_row['PENG_leader'], $money_payout, $pdo);

        header("location: ?side=pengeinnkreving&v=" . $money_payout);
    }
}

?>
<img class="action_image" src="img/action/actions/pengeinnkreving.png" />

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

    echo feedback('Gruppen har nylig utført pengeinnkreving. Det er <t id="countdowntimer">' . $minutes . ' minutter og ' . $seconds . ' sekunder</t> før gruppen kan utføre en ny pengeinnkreving.', 'cooldown'); ?>
    <script>
        timeleft(<?php echo $time_left; ?>, "countdowntimer");
    </script>

    <?php

} else {

    if ($PENG_row['PENG_leader'] != $_SESSION['ID']) {
        echo feedback('Det er kun lederen som kan utføre handlinger', 'blue');
    } else { ?>
        <?= '<p style="text-align: center;" class="description">Utstyr: ' . $utstyrName[$PENG_row['PENG_equipment']] . '</p>'; ?>

        <table>
            <tr>
                <th style="width: 50%;">Handling</th>
                <th style="width: 15%;">Ventetid</th>
                <th style="width: 15%;">Sjanse</th>
                <th style="width: 20%;">Utbetaling</th>
            </tr>
            <?php for ($i = 0; $i < count($handlingName); $i++) { ?>
                <tr class="cursor_hover clickable-row" data-href="?side=pengeinnkreving&alt=<?php echo $i; ?>">
                    <td><?= $handlingName[$i]; ?></td>
                    <td><?= $cooldown[$i]; ?>s</td>
                    <td><?= colorize_chances($chance[$i]); ?></td>
                    <td><?= number($payoutFrom[$i]); ?> - <?= number($payoutTo[$i]); ?> kr</td>
                </tr>
            <?php } ?>
        </table>
<?php }
} ?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            $(".clickable-row").unbind("click"); //Fjern click-event-listener for alle .clickable-row's
            window.location = $(this).data("href");
        });
    });
</script>