<?php

$firms = array('7-Eleven', 'Levis butikk', 'Restaurant', 'Aksjemeglerhus', 'Strømselskap', 'Skipsmegler i Maritim foretning');
$price = array(25000000, 75000000, 150000000, 500000000, 1500000000, 5000000000);
$payout = array(200000, 372000, 800000, 1400000, 2500000, 7500000);
$maxAmount = array(5, 4, 3, 2, 1, 1);

function numberOfFirms($acc_id, $type, $pdo)
{
    $stmt = $pdo->prepare("SELECT count(*) FROM firma WHERE FIRM_acc_id = ? AND FIRM_type = ?");
    $stmt->execute([$acc_id, $type]);
    $amount = $stmt->fetchColumn();

    return $amount;
}

if (isset($_GET['bought'])) {
    echo feedback('Du kjøpte ' . $firms[$_GET['bought']], 'success');
}

if (isset($_GET['buy'])) {
    $alt = $_GET['buy'];

    if (!is_numeric($alt) || $alt > count($firms)) {
        echo feedback('Ugyldig ID', 'fail');
    } elseif (numberOfFirms($_SESSION['ID'], $alt, $pdo) == $maxAmount[$alt]) {
        echo feedback('Du har allerede maks antall ' . $firms[$alt], 'fail');
    } elseif ($price[$alt] > AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
        echo feedback('Du har ikke råd til ' . $firms[$alt], 'fail');
    } else {
        $sql = "INSERT INTO firma (FIRM_acc_id, FIRM_type) VALUES (?,?)";
        $pdo->prepare($sql)->execute([$_SESSION['ID'], $alt]);

        take_money($_SESSION['ID'], $price[$alt], $pdo);

        header("Location: ?side=firma&bought=$alt");
    }
}


$total_firms = 0;
for ($i = 0; $i < count($firms); $i++) {
    $total_firms = $total_firms + numberOfFirms($_SESSION['ID'], $i, $pdo);
}

$money_payout_total = 0;
for ($i = 0; $i < count($firms); $i++) {
    $money_payout_total = $money_payout_total + ($payout[$i] * numberOfFirms($_SESSION['ID'], $i, $pdo));
}

$money_payout_ready = 0;
$sql = "SELECT * FROM firma WHERE FIRM_acc_id = " . $_SESSION['ID'] . " AND FIRM_collected = 0";
$stmt = $pdo->query($sql);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $money_payout_ready = $money_payout_ready + $payout[$row['FIRM_type']];
}

if (isset($_GET['moneyOut'])) {
    echo feedback("Du tok ut " . number($_GET['moneyOut']) . " kr fra firmaene dine", 'success');
}

if (isset($_POST['money_out'])) {
    give_money($_SESSION['ID'], $money_payout_ready, $pdo);

    $sql = "UPDATE firma SET FIRM_collected = 1 WHERE FIRM_acc_id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['ID']]);

    header("Location: ?side=firma&moneyOut=$money_payout_ready");
}

?>

<div class="col-6 single">
    <div class="content">
        <h4>Firma</h4>
        <p class="description">
            Med firma kan du hente ut inntekt hver time. Hvor mye du får utbetalt per time
            varierer på hvilket firma du eier. Dersom man er medlem i en familie kan man kjøpe
            firmaboost for en gitt pengesum som kan gi medlemmene i familien 10%, 25% og 50% ekstra utbetaling
            per time.
        </p>
        <center style="margin-bottom: 10px;">
            <p>Du eier <?= number($total_firms) ?> firmaer og får <?= number($money_payout_total) ?> kr utbetalt per time</p>
            <?php if ($total_firms > 0 && $money_payout_ready > 0) { ?>
                <form method="post">
                    <input type="submit" name="money_out" value="Ta ut <?= number($money_payout_ready) ?> kr fra firma">
                </form>
            <?php
            } ?>
        </center>
        <?php

        $i = 0;
        foreach ($firms as &$value) {
            $hasAll = numberOfFirms($_SESSION['ID'], $i, $pdo) == $maxAmount[$i];
        ?>

            <center style="margin-bottom: 50px;">
                <h4><?= $firms[$i] ?></h4>
                <img src="img/action/firma/<?= $i ?>.png" />
                <div style="max-width: 384px; display: flex; justify-content: space-between">
                    <p style="text-align: left;"><?php if (!$hasAll) { ?> Pris: <?= number($price[$i]) ?> kr<br> <?php } ?>
                        Utbetaling pr time:
                        <?php

                        if (numberOfFirms($_SESSION['ID'], $i, $pdo) > 0) {
                            echo number($payout[$i] * numberOfFirms($_SESSION['ID'], $i, $pdo));
                        } else {
                            echo number($payout[$i]);
                        }
                        ?>

                        kr
                    </p>
                    <p class=" <?= $hasAll ? 'hasALl' : 'notAll' ?>">Du har: <?= number(numberOfFirms($_SESSION['ID'], $i, $pdo)) ?> av <?= number($maxAmount[$i]) ?></p>
                </div>
                <?php if (numberOfFirms($_SESSION['ID'], $i, $pdo) < $maxAmount[$i]) { ?>
                    <a class="a_as_button" href="?side=firma&buy=<?= $i ?>">Kjøp <?= $firms[$i] ?></a>
                <?php } ?>
            </center>
        <?php
            $i++;
        }

        ?>
    </div>
</div>