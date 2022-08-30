<?php

if (has_eiendeler($_SESSION['ID'], $pdo)) {
    $stmt = $pdo->prepare("SELECT FP_owner, FP_city FROM flyplass WHERE FP_owner = ?");
    $stmt->execute([$_SESSION['ID']]);
    $row_fp = $stmt->fetch();
    if ($row_fp) {
        $flyplass = true;
        $flyplass_city = $row_fp['FP_city'];
    } else {
        $flyplass = false;
        $flyplass_city = null;
    }

    $stmt = $pdo->prepare("SELECT KFOW_acc_id, KFOW_city FROM kf_owner WHERE KFOW_acc_id = ?");
    $stmt->execute([$_SESSION['ID']]);
    $row_kf = $stmt->fetch();
    if ($row_kf) {
        $kulefabrikk = true;
        $kulefabrikk_city = $row_kf['KFOW_city'];
    } else {
        $kulefabrikk = false;
        $kulefabrikk_city = null;
    }

    $stmt = $pdo->prepare("SELECT FENGDI_acc_id, FENGDI_city FROM fengsel_direktor WHERE FENGDI_acc_id = ?");
    $stmt->execute([$_SESSION['ID']]);
    $row_jail = $stmt->fetch();
    if ($row_jail) {
        $fengsel = true;
        $fengsel_city = $row_jail['FENGDI_city'];
    } else {
        $fengsel = false;
        $fengsel_city = null;
    }

    $stmt = $pdo->prepare("SELECT RCOWN_acc_id, RCOWN_city FROM rc_owner WHERE RCOWN_acc_id = ?");
    $stmt->execute([$_SESSION['ID']]);
    $row_rc = $stmt->fetch();
    if ($row_rc) {
        $streetrace = true;
        $streetrace_city = $row_rc['RCOWN_city'];
    } else {
        $streetrace = false;
        $streetrace_city = null;
    }

    $stmt = $pdo->prepare("SELECT BJO_owner, BJO_city FROM blackjack_owner WHERE BJO_owner = ?");
    $stmt->execute([$_SESSION['ID']]);
    $row_bj = $stmt->fetch();
    if ($row_bj) {
        $blackjack = true;
        $blackjack_city = $row_bj['BJO_city'];
    } else {
        $blackjack = false;
        $blackjack_city = null;
    }

    $stmt = $pdo->prepare("SELECT BUNOWN_acc_id, BUNOWN_city FROM bunker_owner WHERE BUNOWN_acc_id = ?");
    $stmt->execute([$_SESSION['ID']]);
    $row_bj = $stmt->fetch();
    if ($row_bj) {
        $bunker = true;
        $bunker_city = $row_bj['BUNOWN_city'];
    } else {
        $bunker = false;
        $bunker_city = null;
    }
}

$bedrift_type[0] = 'Flyplass';
$bedrift_type[1] = 'Kulefabrikk';
$bedrift_type[2] = 'Fengsel';
$bedrift_type[3] = 'Streetrace';
$bedrift_type[4] = 'Blackjack';
$bedrift_type[5] = 'Bunker';

$has_bedrift[0] = $flyplass;
$has_bedrift[1] = $kulefabrikk;
$has_bedrift[2] = $fengsel;
$has_bedrift[3] = $streetrace;
$has_bedrift[4] = $blackjack;
$has_bedrift[5] = $bunker;

$city_bedrift[0] = $flyplass_city;
$city_bedrift[1] = $kulefabrikk_city;
$city_bedrift[2] = $fengsel_city;
$city_bedrift[3] = $streetrace_city;
$city_bedrift[4] = $blackjack_city;
$city_bedrift[5] = $bunker_city;

?>

<div class="col-8 single">
    <div class="content">
        <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
        <?php if (!has_eiendeler($_SESSION['ID'], $pdo)) { ?>
            <p class="description" style="text-align: center;">Kjøp bedrifter for å få tilgang til oversikt over inntekt av en bedrift</p>
        <?php } else { ?>
            <p class="description" style="text-align: center;">
                Under vil du se en oversikt over dine bedrifter
                <br>
                Ved midnatt vil de siste 20 transaksjonene for dagen bli fjernet
            </p>
        <?php } ?>
    </div>
</div>

<?php

for ($i = 0; $i < count($bedrift_type); $i++) {
    if ($has_bedrift[$i]) {

?>

        <div class="col-8 single" style="padding-top: 10px;">
            <div class="content">
                <h3 style="margin: 0 0 1rem 0"><?php echo $bedrift_type[$i]; ?> i <?php echo city_name($city_bedrift[$i]) ?></h3>
                <h4>Siste 20 transaksjoner</h4>
                <p class="description">Som eier av <?php echo $bedrift_type[$i]; ?> i <?php echo city_name($city_bedrift[$i]) ?> har du mulighet for å se de 20 siste transaksjonene for i dag</p>
                <table>
                    <tr>
                        <th>Tid</th>
                        <th>Bruker</th>
                        <th>Beløp</th>
                    </tr>
                    <?php

                    $query = $pdo->prepare('SELECT * FROM bedrift_inntekt WHERE BEIN_bedrift_type = :type AND BEIN_city = :city ORDER BY BEIN_date DESC LIMIT 20');
                    $query->execute(array(':type' => $i, ':city' => $city_bedrift[$i]));

                    foreach ($query as $row) {

                    ?>
                        <tr>
                            <td><?php echo date_to_text($row['BEIN_date']); ?></td>
                            <td><?php echo ACC_username($row['BEIN_acc_id'], $pdo); ?></td>
                            <td><?php echo number($row['BEIN_money']); ?> kr</td>
                        </tr>
                    <?php } ?>
                </table>
                <h4 style="margin: 1rem 0;">Bedriftsinntekter <span class="description">(Siste 10 dager)</span></h4>
                <table>
                    <tr>
                        <th>Dato</th>
                        <th>Inntekt</th>
                    </tr>
                    <?php

                    $query = $pdo->prepare('SELECT * FROM bedrift_daily WHERE BEDA_type = :type AND BEDA_city = :city ORDER BY BEDA_date DESC LIMIT 10');
                    $query->execute(array(':type' => $i, ':city' => $city_bedrift[$i]));

                    foreach ($query as $row) {

                    ?>
                        <tr>
                            <td><?php echo day_and_month_to_date($row['BEDA_date']); ?></td>
                            <td><?php echo number($row['BEDA_amount'], $pdo); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
<?php }
} ?>