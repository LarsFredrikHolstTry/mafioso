<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';
include_once '../../functions/oppdrag.php';

$money = remove_space($_POST['money']);

if (isset($money)) {
    user_log($_SESSION['ID'], 'banken', 'Setter inn penger', $pdo);
    $number = $money;
    if (number_valid($number) && $number <= AS_session_row($_SESSION['ID'], 'AS_money', $pdo)) {
        take_money($_SESSION['ID'], $number, $pdo);
        give_bank_money($_SESSION['ID'], $number, $pdo);

        if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 0) {
            mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
        }

        user_log($_SESSION['ID'], 'banken', 'Setter inn: ' . number($number) . ' kr', $pdo);
        echo "Du satt inn " . number($number) . " kr i banken";
    } else {
        user_log($_SESSION['ID'], 'banken', 'Kan ikke sette inn mer enn man har på hånden', $pdo);
        echo "Du kan ikke sette mer penger i banken enn du har på hånden";
    }
}
