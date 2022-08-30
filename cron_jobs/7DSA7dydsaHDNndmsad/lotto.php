<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../functions/functions.php';
include_once '../../functions/statistics.php';

/* LODD TREKNING START */
$total_coupon = lotto_total_coupon($pdo);

if($total_coupon > 0){
    $price_jackpot = 4500;
    $jackpot = lotto_jackpot($pdo) * $price_jackpot;
    $winning_number = mt_rand(1, $total_coupon);
    $number_min = 0;
    $number_max = 0;
    $exp = 50;
    $winner;

    $max_winners = 1;
    
        $sql = "SELECT * FROM lotto ORDER BY LOTTO_id";
        $stmt = $pdo->query($sql);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $number_max = $number_max + $row['LOTTO_amount'];

            if($winning_number >= $number_min && $winning_number <= $number_max){
                $winner = $row['LOTTO_acc_id'];
            }
            
            $number_min = $number_min + $row['LOTTO_amount'];
        }

        $jackpot = $jackpot;

        give_bank_money($winner, $jackpot, $pdo);
        give_exp($winner, $exp, $pdo);
        check_rankup($winner, AS_session_row($winner, 'AS_rank', $pdo), AS_session_row($winner, 'AS_exp', $pdo), $pdo);

        update_gambling($winner, $jackpot, $pdo);

        $text = "Gratulerer! Du vant ".number($jackpot)." kr på lotto";
        send_notification($winner, $text, $pdo);

        $text = "vant ".number($jackpot)." kr i lotto!";
    
        check_rankup($winner, AS_session_row($winner, 'AS_rank', $pdo), AS_session_row($winner, 'AS_exp', $pdo), $pdo);

        $sql = "INSERT INTO last_events (LAEV_user, LAEV_text, LAEV_date) VALUES (?,?,?)";
        $stmt= $pdo->prepare($sql);
        $stmt->execute([$winner, $text, time()]);

        $sql = "DELETE FROM lotto";
        $stmt= $pdo->prepare($sql);
        $stmt->execute();

        $sql = "INSERT INTO lotto_winner (LOTTOW_acc_id, LOTTOW_sum, LOTTOW_date) VALUES (?,?,?)";
        $pdo->prepare($sql)->execute([$winner, $jackpot, time()]);
}
/* LODD TREKNING SLUTT */
