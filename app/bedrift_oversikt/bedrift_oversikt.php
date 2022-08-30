<?php

if (isset($_GET['type']) && isset($_GET['city']) && is_numeric($_GET['type']) && is_numeric($_GET['city'])) {
    echo feedback("Manglende dager kan bety at firmaet har vært uten eier disse dagene og pengene har derfor gått til staten", "blue");

    $type = $_GET['type'];
    $city = $_GET['city'];

    if ($type == 0) {
        $stmt = $pdo->prepare("SELECT FP_owner, FP_city FROM flyplass WHERE FP_city = ?");
        $stmt->execute([$city]);
        $row_fp = $stmt->fetch();

        $flyplass = true;
        $flyplass_city = $row_fp['FP_city'];
    } elseif ($type == 1) {
        $stmt = $pdo->prepare("SELECT KFOW_acc_id, KFOW_city FROM kf_owner WHERE KFOW_city = ?");
        $stmt->execute([$city]);
        $row_kf = $stmt->fetch();

        $kulefabrikk = true;
        $kulefabrikk_city = $row_kf['KFOW_city'];
    } elseif ($type == 2) {
        $stmt = $pdo->prepare("SELECT FENGDI_acc_id, FENGDI_city FROM fengsel_direktor WHERE FENGDI_city = ?");
        $stmt->execute([$city]);
        $row_jail = $stmt->fetch();

        $fengsel = true;
        $fengsel_city = $row_jail['FENGDI_city'];
    } elseif ($type == 3) {
        $stmt = $pdo->prepare("SELECT RCOWN_acc_id, RCOWN_city FROM rc_owner WHERE RCOWN_city = ?");
        $stmt->execute([$city]);
        $row_rc = $stmt->fetch();

        $streetrace = true;
        $streetrace_city = $row_rc['RCOWN_city'];
    } elseif ($type == 4) {
        $stmt = $pdo->prepare("SELECT BJO_owner, BJO_city FROM blackjack_owner WHERE BJO_city = ?");
        $stmt->execute([$city]);
        $row_bj = $stmt->fetch();

        $blackjack = true;
        $blackjack_city = $row_bj['BJO_city'];
    } elseif ($type == 5) {
        $stmt = $pdo->prepare("SELECT BUNOWN_acc_id, BUNOWN_city FROM bunker_owner WHERE BUNOWN_city = ?");
        $stmt->execute([$city]);
        $row_bj = $stmt->fetch();

        $bunker = true;
        $bunker_city = $row_bj['BJO_city'];
    }

    $bedrift_type[0] = 'Flyplass';
    $bedrift_type[1] = 'Kulefabrikk';
    $bedrift_type[2] = 'Fengsel';
    $bedrift_type[3] = 'Streetrace';
    $bedrift_type[4] = 'Blackjack';
    $bedrift_type[5] = 'Bunker';

?>
    <div class="col-8 single">
        <div class="content">
            <img class="action_image" src="img/action/actions/bedrift.png">
            <p class="description" style="text-align: center;">
                Under vil du se en oversikt over inntekt for bedrift</p>

            <div class="col-12 single" style="padding-top: 10px;">
                <div class="content">
                    <h3 style="margin: 0 0 1rem 0"><?php echo $bedrift_type[$type]; ?> i <?php echo city_name($city) ?></h3>
                    <h4 style="margin: 1rem 0;">Bedriftsinntekter <span class="description">(Siste 10 dager)</span></h4>
                    <table>
                        <tr>
                            <th>Dato</th>
                            <th>Inntekt</th>
                        </tr>
                        <?php

                        $query = $pdo->prepare('SELECT * FROM bedrift_daily WHERE BEDA_type = :type AND BEDA_city = :city ORDER BY BEDA_date DESC LIMIT 10');
                        $query->execute(array(':type' => $type, ':city' => $city));

                        foreach ($query as $row) {

                        ?>
                            <tr>
                                <td><?php echo day_and_month_to_date($row['BEDA_date']); ?></td>
                                <td><?php echo number($row['BEDA_amount'], $pdo); ?> kr</td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo feedback("Ugyldig bedrift og by", "error");
}
