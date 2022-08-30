<?php

$equipment[0] = "Bil";
$equipment[1] = "Båt";
$equipment[2] = "Fly";

$equipment_price[0] = 2500000;
$equipment_price[1] = 25000000;
$equipment_price[2] = 250000000;

$equipment_pr_krim[0] = 1250;
$equipment_pr_krim[1] = 12500;
$equipment_pr_krim[2] = 125000;

$equipment_point_price[0] = 0;
$equipment_point_price[1] = 0;
$equipment_point_price[2] = 0;

$equipment_demand[0] = 2;
$equipment_demand[1] = 5;
$equipment_demand[2] = 10;

$money_back[0] = $equipment_price[0] / 2;
$money_back[1] = $equipment_price[1] / 2;
$money_back[2] = $equipment_price[2] / 2;

$max = 17;

if (isset($_GET['buy'])) {
    user_log($_SESSION['ID'], $side, "Kjøper utstyr", $pdo);
    $legal = array(0, 1, 2);
    $buy_option = $_GET['buy'];

    if (total_equipment($_SESSION['ID'], $pdo) >= $max) {
        user_log($_SESSION['ID'], $side, "Har allerede max 17 utstyr", $pdo);
        echo feedback("Du har allerede maks utstyr som er 17", "error");
    } else {
        if (in_array($buy_option, $legal) && is_numeric($buy_option)) {
            if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) >= $equipment_price[$buy_option]) {
                if ($equipment_point_price[$buy_option] > 0 && AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $equipment_point_price[$buy_option]) {
                    $sql = "INSERT INTO smugling (SMUG_acc_id, SMUG_type) VALUES (?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['ID'], $buy_option]);

                    take_money($_SESSION['ID'], $equipment_price[$buy_option], $pdo);
                    take_poeng($_SESSION['ID'], $equipment_point_price[$buy_option], $pdo);

                    user_log($_SESSION['ID'], $side, "Kjøpte " . mb_strtolower($equipment[$buy_option], 'UTF-8'), $pdo);
                    echo feedback('Du kjøpte ' . mb_strtolower($equipment[$buy_option], 'UTF-8') . ' til ditt kartell.', "success");
                } elseif ($equipment_point_price[$buy_option] == 0) {
                    $sql = "INSERT INTO smugling (SMUG_acc_id, SMUG_type) VALUES (?,?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$_SESSION['ID'], $buy_option]);

                    take_money($_SESSION['ID'], $equipment_price[$buy_option], $pdo);

                    user_log($_SESSION['ID'], $side, "Kjøpte " . $buy_option, $pdo);
                    header("Location: ?side=smugling&buy_feedback=$buy_option");
                } else {
                    user_log($_SESSION['ID'], $side, "Ikke nok poeng", $pdo);
                    echo feedback("Du har ikke nok poeng til å kjøpe " . mb_strtolower($equipment[$buy_option], 'UTF-8'), "fail");
                }
            } else {
                user_log($_SESSION['ID'], $side, "Ikke nok penger", $pdo);
                echo feedback("Du har ikke nok penger til å kjøpe " . mb_strtolower($equipment[$buy_option], 'UTF-8'), "fail");
            }
        } else {
            user_log($_SESSION['ID'], $side, "Ugyldig ID: " . $buy_option, $pdo);
            echo feedback("Ugyldig ID", "fail");
        }
    }
}

if (isset($_GET['buy_feedback'])) {
    $legal = array(0, 1, 2);

    if (in_array($_GET['buy_feedback'], $legal)) {
        echo feedback('Du kjøpte ' . mb_strtolower($equipment[$_GET['buy_feedback']], 'UTF-8') . ' til ditt kartell.', "success");
    }
}

if (isset($_GET['sell_feedback'])) {
    $legal = array(0, 1, 2);

    if (in_array($_GET['sell_feedback'], $legal)) {
        echo feedback("Du solgte " . $equipment[$_GET['sell_feedback']] . "", "success");
    }
}

$sell_price = 10; // poeng per salg av utstyr

if (isset($_GET['sell'])) {
    user_log($_SESSION['ID'], $side, "Selger utstyr", $pdo);
    $legal = array(0, 1, 2);
    $sell_option = $_GET['sell'];

    if (in_array($sell_option, $legal) && is_numeric($sell_option)) {
        if (equipment_exist($_SESSION['ID'], $sell_option, $pdo)) {
            if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) >= $sell_price) {
                $money_back = ($equipment_price[$sell_option] / 2);
                remove_equipment($_SESSION['ID'], $sell_option, $pdo);
                take_poeng($_SESSION['ID'], $sell_price, $pdo);
                give_money($_SESSION['ID'], $money_back, $pdo);

                user_log($_SESSION['ID'], $side, "Selger:" . $sell_option, $pdo);
                header("Location: ?side=smugling&sell_feedback=$sell_option");
            } else {
                user_log($_SESSION['ID'], $side, "Ikke nok poeng", $pdo);
                echo feedback("Du har ikke nok poeng for å selge antall utstyr", "error");
            }
        } else {
            user_log($_SESSION['ID'], $side, "Ingen av typen: " . $sell_option, $pdo);
            echo feedback("Du har ingen av denne typen utstyr", "error");
        }
    }
}

?>
<div class="col-12 single" style="margin-top: -10px;">
    <div class="col-6">
        <div class="content">
            <img class="action_image" src="img/action/actions/<?php echo $side; ?>.png">
            <p class="description">Ved å smugle kan du tjene ekstra penger hver eneste dag. Desto mer utstyr og transport-midler du har, desto mer penger vil du tjene per gram marijuana du har. Måten smugling fungerer er at du bygger opp et kartell som du suplerer med marijuana ved å utføre spesiell kriminell handling.</p>
        </div>
    </div>

    <div class="col-6">
        <div class="content">
            <h4 style="margin-bottom: 10px;">Smugling-oversikt</h4>
            <table>
                <tr>
                    <th>Gram</th>
                    <th>Penger ved midnatt</th>
                    <th>Krav for utbetaling</th>
                    <th>Boost</th>
                </tr>
                <tr>
                    <td><?php echo number(get_daily_weed($_SESSION['ID'], $pdo)); ?> g</td>

                    <td>
                        <?php

                        if (get_daily_weed($_SESSION['ID'], $pdo) >= smugling_demand($_SESSION['ID'], $pdo)) {
                            $money_by_midnight = smugling_money_midnight($_SESSION['ID'], $pdo) * get_daily_weed($_SESSION['ID'], $pdo);

                            if (AS_session_row($_SESSION['ID'], 'AS_boost', $pdo) == 1) {
                                echo number(smugling_money_midnight($_SESSION['ID'], $pdo) * get_daily_weed($_SESSION['ID'], $pdo) * 2);
                            } else {
                                echo number(smugling_money_midnight($_SESSION['ID'], $pdo) * get_daily_weed($_SESSION['ID'], $pdo));
                            }
                        } else {
                            echo '<span title="Dersom du ikke har oppnådd kravet får du heller ingen penger ved midnatt">0 (?)</span>';
                        } ?></td>

                    <td>
                        <span<?php if (get_daily_weed($_SESSION['ID'], $pdo) < smugling_demand($_SESSION['ID'], $pdo)) { ?> style="color: orange" <?php } else { ?> style="color: green" <?php } ?>>
                            <?php echo number(smugling_demand($_SESSION['ID'], $pdo)); ?>
                            gram</span>
                    </td>

                    <td>x<?php echo AS_session_row($_SESSION['ID'], 'AS_boost', $pdo) + 1 ?></td>
                </tr>
            </table>
        </div>
        <div class="content">
            <h4 style="margin-bottom: 10px;">Utstyrsbutikk</h4>
            <table>
                <tr>
                    <th>Utstyr</th>
                    <th>Kr per g</th>
                    <th>Krav</th>
                    <th>Pris</th>
                </tr>
                <?php for ($i = 0; $i < count($equipment); $i++) { ?>
                    <tr class="cursor_hover clickable-row" data-href="?side=smugling&buy=<?php echo $i; ?>">
                        <td><?php echo $equipment[$i]; ?></td>
                        <td><?php echo number($equipment_pr_krim[$i]); ?></td>
                        <td><?php echo $equipment_demand[$i]; ?></td>
                        <td><?php echo number($equipment_price[$i]);
                            if ($equipment_point_price[$i] > 0) {
                                echo " & " . $equipment_point_price[$i] . " poeng";
                            } ?> kr</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="content">
            <h4 style="margin-bottom: 10px;">Mitt utstyr</h4>
            <p class="description">Pris for å selge utstyr er <?php echo $sell_price; ?> poeng per utstyr.<br>
                Maks antall utstyr du kan eie er <?php echo $max; ?>.</p>
            <table>
                <tr>
                    <th>Utstyr</th>
                    <th>Antall</th>
                    <th>Kr per g</th>
                    <th>Selg</th>
                </tr>
                <?php

                $sql = "SELECT DISTINCT SMUG_type FROM smugling WHERE SMUG_acc_id = " . $_SESSION['ID'] . "";
                $stmt = $pdo->query($sql);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                ?>
                    <tr>
                        <td><?php echo $equipment[$row['SMUG_type']]; ?></td>
                        <td><?php echo amount_of_equipment($_SESSION['ID'], $row['SMUG_type'], $pdo); ?></td>
                        <td><?php echo number($equipment_pr_krim[$row['SMUG_type']]); ?></td>
                        <td><a href="?side=smugling&sell=<?php echo $row['SMUG_type']; ?>">Selg</a></td>
                    </tr>
                <?php


                } ?>
            </table>
        </div>
    </div>
</div>

<style>
    @media only screen and (max-width: 1200px) {

        .sixty,
        .fourty {
            width: 100% !important;
        }
    }

    .float_left {
        float: left;
    }

    .sixty {
        width: 60%
    }

    .float_right {
        float: right;
    }

    .fourty {
        width: 40%
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });

    timer(<?php echo $time_left_krim_; ?>, "countdowntimer_krim");
</script>