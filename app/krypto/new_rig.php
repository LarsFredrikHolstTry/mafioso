<?php

$price_rigg = 500;
$exist = true;

$stmt = $pdo->prepare("SELECT CRR_acc_id FROM crypto_rigs WHERE CRR_acc_id = :id");
$stmt->execute(['id' => $_SESSION['ID']]);
$row_exist = $stmt->fetch();

if (!$row_exist) {
    $exist = false;
    echo feedback('Du har ingen krypto rigger og det koster derfor ingenting å opprette krypto rigg.', "blue");
} else {
    echo feedback('Du har allerede en krypto rigg. En ny krypto rigg koster ' . $price_rigg . ' poeng', "blue");
}

if (isset($_POST['new_rig'])) {
    user_log($_SESSION['ID'], $side, "Kjøper ny rigg", $pdo);
    if ($exist && AS_session_row($_SESSION['ID'], 'AS_points', $pdo) < $price_rigg) {
        user_log($_SESSION['ID'], $side, "Ikke nok poeng", $pdo);
        echo feedback("Du har allerede en krypto rigg og må betale 500 poeng for en ny rigg.", "error");
    } else {
        $chosen = $_POST['cru_id'];

        if($chosen == 1337){
            echo feedback('Ugyldig krypto', 'error');
        } else {
            $query = $pdo->prepare("SELECT * FROM supported_crypto WHERE SUC_id=?");
            $query->execute(array($chosen));
            $row_supported = $query->fetch(PDO::FETCH_ASSOC);
    
            $query = $pdo->prepare("SELECT CRR_crypto FROM crypto_rigs WHERE CRR_crypto = ? AND CRR_acc_id = ?");
            $query->execute(array($chosen, $_SESSION['ID']));
            $row_rigs = $query->fetch(PDO::FETCH_ASSOC);
    
            if (!$row_rigs) {
                if ($exist) {
                    take_poeng($_SESSION['ID'], $price_rigg, $pdo);
                }
    
                $sql = "INSERT INTO crypto_rigs (CRR_acc_id, CRR_crypto, CRR_gpu, CRR_fan, CRR_motherboard, CRR_psu) VALUES (?,?,?,?,?,?)";
                $pdo->prepare($sql)->execute([$_SESSION['ID'], $chosen, 5, 10, 1, 1]);
    
                user_log($_SESSION['ID'], $side, "Kjøper ny rigg vellykket", $pdo);
                echo feedback("Du opprettet en krypto rigg! Kjøp komponenter for å starte miningen", "success");
            } else {
                user_log($_SESSION['ID'], $side, "Har allerede aktiv rigg med valgt krypto", $pdo);
                echo feedback("Du har allerede en rigg med valgt krypto", "error");
            }
        }

    }
}

?>
<div class="col-6 single">
    <div class="content">
        <span class="small_header">OPPRETT NY RIGG <a style="float: right;" href="?side=krypto">LUKK</a></span>

        <h4 style="margin-top: 40px; margin-bottom: 10px;">Velg ønsket krypto</h4>
        <form method="post">
            <div class="custom-select" style="width:100%; margin-bottom: 10px;">
                <select style="width: 100%;" name="cru_id" required>
                    <option value="1337">Velg krypto:</option>
                    <?php

                    $sql = "SELECT SUC_id, SUC_ticker, SUC_price_pr_gpu FROM supported_crypto ORDER BY SUC_ticker";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                        <option value="<?php echo $row['SUC_id']; ?>">
                            <?php
                            echo $row['SUC_ticker'] .
                                ' <span class="description">(Utbetaling pr GPU: ' . $row['SUC_price_pr_gpu'] . ' ' . $row['SUC_ticker'] . ')</span>'; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input style="margin-top: 5px;" type="submit" name="new_rig" value="Opprett rigg">
        </form>
    </div>
</div>

<script src="js/select_custom.js"></script>