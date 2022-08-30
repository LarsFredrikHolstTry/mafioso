<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../functions/functions.php';

// Lagre bedrift-data hvor data faktisk finnes
$query = $pdo->prepare('SELECT * FROM bedrift_inntekt');
$query->execute();
foreach ($query as $row) {
    $sql = "INSERT INTO bedrift_inntekt_history (BEIN_acc_id, BEIN_bedrift_type, BEIN_city, BEIN_money, BEIN_date) VALUES (?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$row['BEIN_acc_id'], $row['BEIN_bedrift_type'], $row['BEIN_city'], $row['BEIN_money'], $row['BEIN_date']]);
}

$amount = 0;
for ($i = 0; $i < 6; $i++) {
    for ($city = 0; $city < 6; $city++) {
        $query = $pdo->prepare("SELECT SUM(BEIN_money) as sum FROM bedrift_inntekt WHERE BEIN_bedrift_type = ? AND BEIN_city = ?");
        $query->execute(array($i, $city));
        $sum = $query->fetch(PDO::FETCH_ASSOC);
        $sum = $sum['sum'];

        if (!$sum) {
            $sum = 0;
        }

        $sql = "INSERT INTO bedrift_daily (BEDA_type, BEDA_city, BEDA_amount, BEDA_date) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$i, $city, $sum, time()]);
    }
}

// Fjern 11 gamle dager gamle data
$eleven_days = 950400;
$sql = "DELETE FROM bedrift_inntekt";
$pdo->exec($sql);
$sql = "DELETE FROM bedrift_daily WHERE BEDA_date < " . (time() - $eleven_days) . "";
$pdo->exec($sql);


$money_prize[0] = 50000000;
$money_prize[1] = 40000000;
$money_prize[2] = 30000000;
$money_prize[3] = 20000000;
$money_prize[4] = 10000000;

$place = 0;
$best_family = get_best_fam($pdo);
if($best_family) {
    $sql =
        "SELECT accounts_stat.AS_id, accounts_stat.AS_daily_exp
        FROM accounts_stat 
        INNER JOIN family_member
        ON accounts_stat.AS_id = family_member.FAMMEM_acc_id 
        WHERE family_member.FAMMEM_fam_id = " . $best_family .
        "ORDER BY accounts_stat.AS_daily_exp DESC LIMIT 5";

    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        give_bank_money($row['AS_id'], $money_prize[$place], $pdo);

        $exp = $row['AS_daily_exp'];
        $to = $row['AS_id'];

        $text = "Familien din skaffet mest EXP til midnatt. Du skaffet " . number($exp) . " EXP for familien din og får " . number($money_prize[$place]) . " kr i belønnelse!";
        send_notification($to, $text, $pdo);

        $place++;
    }
}