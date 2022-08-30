<?php

$rig_name[0] = 'PSU';
$rig_name[1] = 'Hovedkort';
$rig_name[2] = 'Vifte';
$rig_name[3] = 'Skjermkort';

$rig_max[0] = '2000';
$rig_max[1] = '2000';
$rig_max[2] = '20000';
$rig_max[3] = '10000';

$rig_price[0] = '50000';
$rig_price[1] = '100000';
$rig_price[2] = '20000';
$rig_price[3] = '850000';

$rig_image[0] = '001-power-supply.svg';
$rig_image[1] = '002-motherboard.svg';
$rig_image[2] = '003-fan.svg';
$rig_image[3] = '004-graphic-card.svg';

$rig_bg[0] = '234c6e';
$rig_bg[1] = '653e52';
$rig_bg[2] = '654c4a';
$rig_bg[3] = '4a4c73';

$pig_price = 500;

if (isset($_GET['buy'])) {
    echo feedback("Du kjøpte komponenter til mining riggen", "success");
}

if (isset($_GET['pigBuy'])) {
    echo feedback("Du kjøpte sparegris miner!", "success");
}

if (isset($_POST['buy_pig'])) {
    user_log($_SESSION['ID'], $side, "Kjøper sparegris miner", $pdo);
    $stmt = $pdo->prepare("SELECT * FROM crypto_rigs WHERE CRR_id = :id");
    $stmt->execute(['id' => $_GET['edit_rig']]);
    $row = $stmt->fetch();

    if ($row['CRR_pig'] == 0) {
        if (AS_session_row($_SESSION['ID'], 'AS_points', $pdo) < $pig_price) {
            user_log($_SESSION['ID'], $side, "Ikke nok poeng", $pdo);
            echo feedback("Du har ikke nok poeng for å kjøpe sparegris miner", "error");
        } else {
            $sql = "UPDATE crypto_rigs SET CRR_pig = ? WHERE CRR_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([1, $_GET['edit_rig']]);

            take_poeng($_SESSION['ID'], $pig_price, $pdo);

            user_log($_SESSION['ID'], $side, "Sparegris miner kjøpt", $pdo);
            header("Location: ?side=krypto&edit_rig=" . $_GET['edit_rig'] . "&pigBuy");
        }
    }
}

for ($i = 0; $i < count($rig_name); $i++) {
    $stmt = $pdo->prepare("SELECT * FROM crypto_rigs WHERE CRR_id = :id");
    $stmt->execute(['id' => $_GET['edit_rig']]);
    $row = $stmt->fetch();

    $rig_spec[0] = $row['CRR_psu'];
    $rig_spec[1] = $row['CRR_motherboard'];
    $rig_spec[2] = $row['CRR_fan'];
    $rig_spec[3] = $row['CRR_gpu'];

    $db_field_name[0] = 'CRR_psu';
    $db_field_name[1] = 'CRR_motherboard';
    $db_field_name[2] = 'CRR_fan';
    $db_field_name[3] = 'CRR_gpu';

    if (isset($_POST['buy_' . $i])) {
        user_log($_SESSION['ID'], $side, "Kjøper utstyr til kryptorigg", $pdo);
        $amount = remove_space($_POST['amount_' . $i]);

        if (is_numeric($amount) && $amount > 0) {
            if ($rig_spec[$i] + $amount <= $rig_max[$i]) {
                $price = $amount * $rig_price[$i];
                if ($price <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
                    $sql = "UPDATE crypto_rigs SET " . $db_field_name[$i] . " = " . $db_field_name[$i] . " + ? WHERE CRR_id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$amount, $_GET['edit_rig']]);

                    take_money($_SESSION['ID'], $price, $pdo);

                    user_log($_SESSION['ID'], $side, "Kjøpte utstyr til krypto rigg", $pdo);
                    header("Location: ?side=krypto&edit_rig=" . $_GET['edit_rig'] . "&buy=true");
                } else {
                    user_log($_SESSION['ID'], $side, "Ikke nok penger", $pdo);
                    echo feedback("Ikke nok penger", "error");
                }
            } else {
                user_log($_SESSION['ID'], $side, "Ugyldig antall: " . $amount, $pdo);
                echo feedback("Ugyldig antall", "error");
            }
        } else {
            user_log($_SESSION['ID'], $side, "Ugyldig antall: " . $amount, $pdo);
            echo feedback("Ugyldig antall", "error");
        }
    }
}

if (isset($_GET['edit_rig'])) {
    if (is_numeric($_GET['edit_rig'])) {
        $stmt = $pdo->prepare("SELECT * FROM crypto_rigs WHERE CRR_id = :id");
        $stmt->execute(['id' => $_GET['edit_rig']]);
        $row = $stmt->fetch();

        if ($row['CRR_acc_id'] == $_SESSION['ID']) {

            $rig_spec[0] = $row['CRR_psu'];
            $rig_spec[1] = $row['CRR_motherboard'];
            $rig_spec[2] = $row['CRR_fan'];
            $rig_spec[3] = $row['CRR_gpu'];

            $pig = $row['CRR_pig'];

            if ($pig) {
                $multiplyer = 2;
            } else {
                $multiplyer = 1;
            }

            $stmt = $pdo->prepare("SELECT SUC_name, SUC_price, SUC_price_pr_gpu FROM supported_crypto WHERE SUC_id = :id");
            $stmt->execute(['id' => $row['CRR_crypto']]);
            $row_supported = $stmt->fetch();

?>
            <div class="col-12">
                <div class="col-6">
                    <div class="content">
                        <span class="small_header">Rediger rigg for <?php echo $row_supported['SUC_name']; ?><a style="float: right;" href="?side=krypto">LUKK</a></span>
                        <?php if ($row['CRR_pig'] == 1) {
                        } ?>
                        <h4 style="margin-top: 40px;"><?php echo $row_supported['SUC_name']; ?> miner</h4>
                        <p>Utbetaling per time:
                            <?php
                            if (rigg_stabil($row['CRR_psu'], $row['CRR_motherboard'], $row['CRR_fan'], $row['CRR_gpu'])) {
                                echo number_format(($row['CRR_gpu'] * $row_supported['SUC_price_pr_gpu']) * $multiplyer, 2, '.', ' ') . ' ' . $row_supported['SUC_name'];
                                echo ' <span class="description">(';
                                echo number((($row['CRR_gpu'] * $row_supported['SUC_price_pr_gpu']) * $row_supported['SUC_price']) * $multiplyer) . ' kr';
                                echo ')</span>';
                            } else {
                                echo 'Ustabil miner gir ikke utbetaling';
                            }
                            ?>

                            <?php
                            if ($pig) {
                                echo '</p>Sparegris miner Aktivert</p>';
                            } else {
                            ?>
                        <form method="post">
                            <input type="submit" name="buy_pig" value="Kjøp sparegris miner (500 poeng)">
                        </form>
                    <?php
                            }
                    ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="content">
                        <span class="small_header">Guide</span>
                        <p>
                            For å ikke overopphete mining riggen din må du følge disse guidene:<br>
                            Maks 5 skjermkort per hovedkort<br>
                            Minst 2 vifter per skjermkort<br>
                            Maks 1 PSU per hovedkort</p>
                    </div>
                </div>
            </div>
            <?php for ($i = 0; $i < count($rig_name); $i++) { ?>
                <div class="col-3">
                    <div class="content">

                        <div style="height: 25px; width: auto;">
                            <div class="horizontal_center" style="float: left; border-radius: 3px; width: 40px; height: 40px; background-color: #<?php echo $rig_bg[$i]; ?>;">
                                <img style="width: 25px; height: auto;" src="img/crypto/components/<?php echo $rig_image[$i]; ?>">
                            </div>
                            <span style="float: left; margin: 10px 8px;" class="small_header"><?php echo $rig_name[$i]; ?></span>
                        </div>

                        <br><br>
                        <div class="col-6" style="padding: 0;">
                            <span class="small_header">Pris</span>
                            <br>
                            <b><?php echo number($rig_price[$i]); ?> kr</b>
                        </div>
                        <div class="col-6" style="padding: 0;">
                            <span class="small_header">Antall</span>
                            <br>
                            <b><?php echo number($rig_spec[$i]); ?> / <?php echo number($rig_max[$i]); ?></b>
                        </div>
                        <?php if ($rig_spec[$i] < $rig_max[$i]) { ?>
                            <div class="col-12" style="padding: 0; margin-top: 20px;">
                                <form method="post">
                                    <input type="text" id="input_<?php echo $i; ?>" style="width: 60%;" placeholder="Antall" name="amount_<?php echo $i; ?>">
                                    <input type="submit" style="width: 35%;" value="Kjøp" name="buy_<?php echo $i; ?>">
                                </form>
                            </div>
                        <?php } else {
                            echo '<div style="width: 100%; height: auto; margin-top: 50px;">';
                            echo feedback('Du har maks', "blue");
                            echo '</div>';
                        } ?>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            <?php } ?>
            <div style="clear: both;"></div>

<?php

        } else {
            echo feedback($useLang->error->voidID, 'error');
        }
    } else {
        echo feedback($useLang->error->voidID, 'error');
    }
}
?>

<script>
    number_space("#input_0");
    number_space("#input_1");
    number_space("#input_2");
    number_space("#input_3");
</script>