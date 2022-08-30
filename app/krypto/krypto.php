<?php

if (isset($_GET['edit_rig'])) {
    include 'edit_rig.php';
}

if (isset($_GET['new_rig'])) {
    include 'new_rig.php';
}

if (isset($_GET['kjop'])) {
    include 'kjop_crypto.php';
}

if (isset($_GET['selg'])) {
    include 'selg_crypto.php';
}

if (isset($_POST['buy-crypto'])) {
    header("Location: ?side=krypto&kjop");
}

if (isset($_POST['sell-crypto'])) {
    header("Location: ?side=krypto&selg");
}

if(isset($_POST['sell-all'])){
    $money_to_user = total_krypto($_SESSION['ID'], $pdo);

    $query = $pdo->prepare('SELECT * FROM crypto_user WHERE CRU_acc_id = :id AND CRU_amount > 0');
    $query->execute(array(':id' => $_SESSION['ID']));

    foreach ($query as $row) {
        $sql = "UPDATE crypto_user SET CRU_amount = 0 WHERE CRU_acc_id = ? ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['ID']]);
    }

    give_money($_SESSION['ID'], $money_to_user, $pdo);

    header("Location: ?side=krypto&sell_all_amt=".$money_to_user."");
}

if(isset($_GET['sell_all_amt'])){
    $amount = $_GET['sell_all_amt'];
    if(is_numeric($amount) && $amount > 0){
        echo feedback('Du solgte krypto for '.number($amount).' kr', 'success');
    } else {
        echo feedback('Du kan ikke selge mindre enn 0 krypto', 'fail');
    }
}

?>

<div class="col-12 single">
    <div class="col-6">
        <div class="content">
            <span class="small_header">KRYPTOSALDO</span>
            <h4 style="margin-top: 40px;"><?php echo number(total_krypto($_SESSION['ID'], $pdo)); ?> KR</h4>
            <!-- <span class="description"><?php // echo number_format(total_in_btc($_SESSION['ID'], $pdo), 2, '.', ' '); ?> BTC</span> -->
            <form method="post" style="margin-top: 40px;">
                <div style="display: flex; justify-content: space-evenly;">
                    <input type="submit" name="sell-crypto" value="Selg" style="width: 30%;">
                    <input type="submit" name="buy-crypto" value="Kjøp" style="width: 30%;">
                    <input type="submit" name="sell-all" value="Selg alt"  onclick="return confirm('Er du sikker på at du ønsker å selge all krypto?');"  style="width: 30%;">
                </div>
            </form>
        </div>
    </div>
    <div class="col-6">
        <div class="content">
            <span class="small_header">BEHOLDNING</span>
            <br><br>
            <?php

            $stmt = $pdo->prepare("SELECT CRU_acc_id FROM crypto_user WHERE CRU_acc_id = :id AND CRU_amount > 0");
            $stmt->execute(['id' => $_SESSION['ID']]);
            $row_exist = $stmt->fetch();

            if (!$row_exist) {
                echo feedback("<p style='font-weight: bolder; margin: 0px;'>Ingen krypto funnet</p> <br>Du har ingen krypto. Start en rigg eller kjøp krypto for å få beholdning", "blue");
            } else {

            ?>
                <ul>
                    <?php

                    $query = $pdo->prepare('SELECT * FROM crypto_user WHERE CRU_acc_id = :id AND CRU_amount > 0');
                    $query->execute(array(':id' => $_SESSION['ID']));

                    foreach ($query as $row) {

                        $stmt = $pdo->prepare("SELECT SUC_name, SUC_ticker, SUC_price FROM supported_crypto WHERE SUC_id = :suc_id");
                        $stmt->execute(['suc_id' => $row['CRU_crypto']]);
                        $row_crypto = $stmt->fetch();

                        $crypto_name = $row_crypto['SUC_name'];
                        $crypto_ticker = $row_crypto['SUC_ticker'];
                        $crypto_price = $row_crypto['SUC_price'];

                    ?>
                        <li class="horizontal_center">
                            <div style="width: 10%;">
                                <img style="width: 30px; height: auto; border-radius: 45px; border: 5px solid rgba(255,255,255,.2);" src="img/crypto/ticket/v2/<?php echo strtolower($crypto_ticker); ?>.png">
                            </div>
                            <div style="width: 20%;">
                                <h4 style="text-transform: uppercase;"><?php echo $crypto_ticker ?></h4>
                                <span class="description"><?php echo $crypto_name; ?></span>
                            </div>
                            <div style="width: 70%; text-align: right;">
                                <h4 style="text-transform: uppercase;"><?php echo number($row['CRU_amount']); ?> <?php echo $crypto_ticker; ?></h4>
                                <span class="description"><?php echo number_format(($row['CRU_amount'] * $crypto_price), 2, '.', ' '); ?> kr</span>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            <?php } ?>
            <div style="clear: both;"></div>
        </div>
    </div>
    <div class="col-12">
        <h4 style="margin: 5px 0px 20px 0px;">Mine mining rigger
            <a style="margin-left: 15px;" href="?side=krypto&new_rig" class="a_as_button_secondary">Legg til ny mining rigg</a>
        </h4>
        <?php

        $stmt = $pdo->prepare("SELECT CRR_acc_id FROM crypto_rigs WHERE CRR_acc_id = :id");
        $stmt->execute(['id' => $_SESSION['ID']]);
        $row_exist = $stmt->fetch();

        if ($row_exist) {

            $query = $pdo->prepare('SELECT * FROM crypto_rigs WHERE CRR_acc_id = :id');
            $query->execute(array(':id' => $_SESSION['ID']));

            foreach ($query as $row) {

                $stmt = $pdo->prepare("SELECT SUC_price, SUC_name FROM supported_crypto WHERE SUC_ticker = :suc_ticker");
                $stmt->execute(['suc_ticker' => $row['CRR_crypto']]);
                $row_crypto = $stmt->fetch();

                $crypto_price = $row_crypto ?? $row_crypto['SUC_price'];
                $crypto_name = $row_crypto ?? $row_crypto['SUC_name'];

                $stmt = $pdo->prepare("SELECT * FROM supported_crypto WHERE SUC_id = :id");
                $stmt->execute(['id' => $row['CRR_crypto']]);
                $row_supported_crypto = $stmt->fetch();
        ?>
                <a href="?side=krypto&edit_rig=<?php echo $row['CRR_id']; ?>">
                    <div class="col-3">
                        <div class="content crypto_rigs" <?php if (isset($_GET['edit_rig']) && $_GET['edit_rig'] == $row['CRR_id']) { ?> style="background-color: rgba(0,0,0,.1);" <?php } ?>>
                            <img style="width: 30px; height: auto; border-radius: 45px; border: 5px solid rgba(255,255,255,.2);" src="img/crypto/ticket/v2/<?php echo strtolower($row_supported_crypto['SUC_ticker']); ?>.png">
                            <?php

                            if (rigg_stabil($row['CRR_psu'], $row['CRR_motherboard'], $row['CRR_fan'], $row['CRR_gpu'])) {
                                echo pill('Stabil', 'success');
                            } else {
                                echo pill('Ustabil', 'error');
                            }

                            if ($row['CRR_pig'] == 1) {
                                echo pill('<img style="height: 17px; width: auto;" src="img/crypto/pig.svg">', 'pig');
                            }

                            ?>
                            <h4 style="margin-top: 25px; text-transform: uppercase;"><?php echo $row_supported_crypto['SUC_ticker']; ?></h4>
                            <span class="description"><?php echo $row_supported_crypto['SUC_name']; ?></span>
                            <h4 style="margin-top: 25px; text-transform: uppercase;"><?php echo number_format($row_supported_crypto['SUC_price'], 2, '.', ' '); ?> kr</h4>
                            <span class="description">Pris oppdatert: <?php echo date_to_text($row_supported_crypto['SUC_date']); ?></span>
                        </div>
                    </div>
                </a>
            <?php } ?>
        <?php } ?>
    </div>

</div>