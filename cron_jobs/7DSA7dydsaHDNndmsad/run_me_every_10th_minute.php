<?php

ob_start();
include '../../env.php';
include_once '../../functions/functions.php';

$minutes_pr_person = 10;
$people_in_queoe = in_shout_queue($pdo);
$total_waittime = $minutes_pr_person * $people_in_queoe;

if ($total_waittime == 0 || $total_waittime == null) {
} else {
    $query = $pdo->prepare("SELECT * FROM shout_queue ORDER BY SHTQUE_date ASC LIMIT 1");
    $query->execute(array());
    $SH_row = $query->fetch(PDO::FETCH_ASSOC);

    $id = $SH_row['SHTQUE_id'];
    $acc_id = $SH_row['SHTQUE_acc_id'];
    $message = $SH_row['SHTQUE_message'];

    $sql = "INSERT INTO shout (SH_acc_id, SH_message, SH_date) VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$acc_id, $message, time()]);

    $sql = "DELETE FROM shout_queue WHERE SHTQUE_id = $id";
    $pdo->exec($sql);
}

// Oppdater aksjepriser
$stock_name[0] = "Mafioso Eiendom C";
$stock_name[1] = "Den Sveitsiske bank";
$stock_name[2] = "Det Danske Dampskibselskap";
$stock_name[3] = "Potet Solutions Inc.";
$stock_name[4] = "Fart & Bart AS";

for ($j = 0; $j < count($stock_name); $j++) {
    $query = $pdo->prepare("SELECT * FROM stock_list WHERE SLI_type = ? ORDER BY SLI_date DESC");
    $query->execute(array($j));
    $row_stock = $query->fetch(PDO::FETCH_ASSOC);

    $stock_price[$j] = $row_stock['SLI_price'];

    if ($stock_price[$j] <= 15) {
        $random = mt_rand(-5, 2);
    } else {
        $random = mt_rand(-5, 5);
    }

    $max_price = 170;

    if ($stock_price[$j] + $random > $max_price) {
        $new_price = $stock_price[$j] - mt_rand(10, 20);
    } elseif ($stock_price[$j] + $random <= 0) {
        $statement = $pdo->prepare("SELECT * FROM stock_behold WHERE STB_$j >= :one");
        $statement->execute(array(':one' => 1));
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $text = "$stock_name[$j] har gått konkurs, siden du hadde beholdning i dette selskapet har du mistet dine aksjer.";
            send_notification($row['STB_acc_id'], $text, $pdo);

            $sql = "UPDATE stock_behold SET STB_$j = ? WHERE STB_acc_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([0, $row['STB_acc_id']]);
        }

        $new_price = mt_rand(15, 25);
    } else {
        $new_price = $stock_price[$j] + $random;
    }

    $sql = "INSERT INTO stock_list (SLI_type, SLI_price, SLI_date) VALUES (?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$j, $new_price, time()]);

    $diff = time() + 3600;
    $sql = "DELETE FROM stock_list WHERE SLI_date < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY))";
    $pdo->exec($sql);
}
