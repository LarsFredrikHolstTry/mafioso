<?php

if (isset($_GET['equipment'])) {
    $equipment = $_GET['equipment'];
    if (is_numeric($equipment) && $equipment <= count($utstyrName) && $utstyrName >= 0) {
        if ($utstyrPrice[$equipment] <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {

            $sql = "UPDATE peng_group SET PENG_equipment = ? WHERE PENG_id = ?";
            $pdo->prepare($sql)->execute([$equipment, $pengId]);

            take_money($_SESSION['ID'], $utstyrPrice[$equipment], $pdo);

            header("location: ?side=pengeinnkreving&equipment=" . $equipment . "");
        } else {
            echo feedback('Du har ikke nok penger', 'fail');
        }
    } else {
        echo feedback('Ugyldig ustyr', 'fail');
    }
}

?>
<div style="display: flex; justify-content: space-between">
    <?php
    for ($i = 1; $i < count($utstyrName); $i++) {
    ?>

        <div>
            <h4><?= $utstyrName[$i] ?></h4>
            <p style="margin-bottom: 20px;">
                Sjanseboost: <?= colorize_chances($chanceBoost[$i]) ?>
                <br>
                Pris: <?= number($utstyrPrice[$i]) ?> kr
            </p>
            <a class="a_as_button" href="?side=pengeinnkreving&equipment=<?= $i ?>">Velg utstyr</a>
        </div>
    <?php } ?>
</div>