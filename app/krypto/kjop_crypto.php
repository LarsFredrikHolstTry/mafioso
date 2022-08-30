<?php

if (isset($_GET['id'])) {
    $query = $pdo->prepare("SELECT SUC_price, SUC_ticker FROM supported_crypto WHERE SUC_id=?");
    $query->execute(array($_GET['id']));
    $row = $query->fetch(PDO::FETCH_ASSOC);

    echo feedback("Du kjøpte " . number($_GET['amount']) . " " . $row['SUC_ticker'] . " for " . number($_GET['amount'] * $row['SUC_price']) . " kr", "success");
}

if (isset($_POST['buy_crypto'])) {
    user_log($_SESSION['ID'], $side, "Kjøp krypto", $pdo);
    $chosen = $_POST['cru_id'];
    $amount = remove_space($_POST['amount']);

    $query = $pdo->prepare("SELECT * FROM supported_crypto WHERE SUC_id = ?");
    $query->execute(array($chosen));
    $row_supported = $query->fetch(PDO::FETCH_ASSOC);

    if ($row_supported) {
        if (is_numeric($amount) && $amount >= 1 && $amount * $row_supported['SUC_price'] <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
            if ($row_supported['SUC_ticker'] == 'SHIB' && $amount < 10000) {
                user_log($_SESSION['ID'], $side, "Prøver å kjøpe under 10 000 SHIB", $pdo);
                echo feedback("Du må kjøpe minst 10 000 SHIB", "error");
            } elseif ($row_supported['SUC_ticker'] == 'DOGE' && $amount < 100) {
                user_log($_SESSION['ID'], $side, "Prøver å kjøpe under 100 DOGE", $pdo);
                echo feedback("Du må kjøpe minst 100 DOGE", "error");
            } else {
                $price = $amount * $row_supported['SUC_price'];
                take_money($_SESSION['ID'], $price, $pdo);

                $query = $pdo->prepare("SELECT CRU_crypto FROM crypto_user WHERE CRU_acc_id=? AND CRU_crypto=?");
                $query->execute(array($_SESSION['ID'], $chosen));
                $row = $query->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    $sql = "UPDATE crypto_user SET CRU_amount = CRU_amount + ? WHERE CRU_crypto = ? AND CRU_acc_id = ? ";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$amount, $chosen, $_SESSION['ID']]);
                } else {
                    $sql = "INSERT INTO crypto_user (CRU_amount, CRU_crypto, CRU_acc_id) VALUES (?,?,?)";
                    $pdo->prepare($sql)->execute([$amount, $chosen, $_SESSION['ID']]);
                }

                user_log($_SESSION['ID'], $side, "Kjøper " . $amount . " stk " . $chosen . " for " . $price . " kr", $pdo);
                header("Location: ?side=krypto&kjop&id=" . $chosen . "&amount=" . $amount . "");
            }
        } else {
            user_log($_SESSION['ID'], $side, "Ikke nok penger eller ugyldig antall krypto: " . $amount, $pdo);
            echo feedback("Ikke nok penger eller ugyldig antall krypto", "error");
        }
    } else {
        user_log($_SESSION['ID'], $side, "Ugyldig krypto: " . $amount, $pdo);
        echo feedback("Ugyldig krypto", "error");
    }
}

?>
<div class="col-6 single">
    <div class="content">
        <span class="small_header">Kjøp krypto <a style="float: right;" href="?side=krypto">LUKK</a></span>

        <h4 style="margin-top: 40px; margin-bottom: 10px;">Velg ønsket krypto</h4>
        <form method="post">
            <div class="custom-select" style="width:100%; margin-bottom: 10px;">
                <select style="width: 100%;" name="cru_id" required>
                    <option value="null">Velg krypto:</option>
                    <?php

                    $sql = "SELECT SUC_id, SUC_ticker, SUC_price FROM supported_crypto ORDER BY SUC_ticker";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <option value="<?php echo $row['SUC_id']; ?>">
                            <?php
                            echo $row['SUC_ticker'] .
                                ' <span class="description">(Pris: ' . number($row['SUC_price']) . ' kr)</span>'; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <h4 style="margin-top: 30px; margin-bottom: 10px;">Antall</h4>
            <input type="text" id="number_kjop" style="width: 100%;" name="amount" placeholder="Antall krypto..">
            <input style="margin-top: 5px; width: 100%;" type="submit" name="buy_crypto" value="Kjøp">
        </form>
    </div>
</div>

<script src="js/select_custom.js"></script>
<script>
    number_space("#number_kjop");
</script>