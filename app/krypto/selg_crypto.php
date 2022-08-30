<?php

if (isset($_GET['id'])) {
    $query = $pdo->prepare("SELECT SUC_price, SUC_ticker FROM supported_crypto WHERE SUC_id=?");
    $query->execute(array($_GET['id']));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    echo feedback("Du solgte " . number($_GET['amount']) . " " . $row['SUC_ticker'] . " for " . number($_GET['amount'] * $row['SUC_price']) . " kr", "success");
}

if (isset($_POST['sell_crypto'])) {
    user_log($_SESSION['ID'], $side, "Selger krypto", $pdo);
    $chosen = $_POST['cru_id'];
    $amount = remove_space($_POST['amount']);

    if (is_numeric($amount) && $amount > 0) {
        $query = $pdo->prepare("SELECT * FROM crypto_user WHERE CRU_id = ?");
        $query->execute(array($chosen));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $amount_of_crypto = $row['CRU_amount'];
        $crypto = $row['CRU_crypto'];

        $query = $pdo->prepare("SELECT * FROM supported_crypto WHERE SUC_id = ?");
        $query->execute(array($crypto));
        $row_supported = $query->fetch(PDO::FETCH_ASSOC);

        if ($amount <= $amount_of_crypto) {
            if ($row_supported['SUC_ticker'] == 'SHIB' && $amount < 10000) {
                echo feedback("Du må selge minst 10 000 SHIB", "error");
            } else {
                $money = $row_supported['SUC_price'] * $amount;

                $sql = "UPDATE crypto_user SET CRU_amount = CRU_amount - ? WHERE CRU_id = ? ";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$amount, $chosen]);

                give_money($_SESSION['ID'], $money, $pdo);

                user_log($_SESSION['ID'], $side, "Selger krypto for " . $money . " kr", $pdo);
                header("Location: ?side=krypto&selg&id=" . $row_supported['SUC_id'] . "&amount=" . $amount . "");
            }
        } else {
            user_log($_SESSION['ID'], $side, "Prøver å selge fler enn han har", $pdo);
            echo feedback("Du kan ikke selge flere enn du selv har", "error");
        }
    } else {
        user_log($_SESSION['ID'], $side, "Ugyldig antall: " . $amount, $pdo);
        echo feedback("Ugyldig antall", "error");
    }
}

?>
<div class="col-6 single">
    <div class="content">
        <span class="small_header">Selg krypto <a style="float: right;" href="?side=krypto">LUKK</a></span>

        <h4 style="margin-top: 40px; margin-bottom: 10px;">Velg krypto</h4>
        <form method="post">
            <div class="custom-select" style="width:100%; margin-bottom: 10px;">
                <select style="width: 100%;" name="cru_id" required>
                    <option value="null">Velg krypto:</option>
                    <?php

                    $query = $pdo->prepare('SELECT CRU_amount, CRU_crypto, CRU_id FROM crypto_user WHERE CRU_acc_id = :id AND CRU_amount > 0');
                    $query->execute(array(':id' => $_SESSION['ID']));

                    foreach ($query as $row) {
                        $stmt = $pdo->prepare("SELECT SUC_name, SUC_ticker, SUC_price FROM supported_crypto WHERE SUC_id = :suc_id ");
                        $stmt->execute(['suc_id' => $row['CRU_crypto']]);
                        $row_crypto = $stmt->fetch();

                    ?>
                        <option value="<?php echo $row['CRU_id']; ?>">
                            <?php
                            echo $row_crypto['SUC_ticker'] .
                                ' <span class="description">(Antall: ' . number($row['CRU_amount']) . ')</span>';
                            ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <h4 style="margin-top: 30px; margin-bottom: 10px;">Antall</h4>
            <input type="text" id="number_selg" style="width: 100%;" name="amount" placeholder="Antall krypto..">
            <input style="margin-top: 5px; width: 100%;" type="submit" name="sell_crypto" value="Selg">
        </form>
    </div>
</div>

<script src="js/select_custom.js"></script>
<script>
    number_space("#number_selg");
</script>