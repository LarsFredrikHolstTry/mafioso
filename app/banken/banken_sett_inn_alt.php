<?php

ob_start();
include '../../env.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';
include_once '../../functions/oppdrag.php';

if (AS_session_row($_SESSION['ID'], 'AS_money', $pdo) > 0) {
    user_log($_SESSION['ID'], 'banken', 'Setter inn alle penger', $pdo);
    $number = AS_session_row($_SESSION['ID'], 'AS_money', $pdo);
    take_money($_SESSION['ID'], $number, $pdo);
    give_bank_money($_SESSION['ID'], $number, $pdo);

    if (AS_session_row($_SESSION['ID'], 'AS_mission', $pdo) == 0) {
        mission_update(AS_session_row($_SESSION['ID'], 'AS_mission_count', $pdo) + 1, AS_session_row($_SESSION['ID'], 'AS_mission', $pdo), mission_criteria(AS_session_row($_SESSION['ID'], 'AS_mission', $pdo)), $_SESSION['ID'], $pdo);
    }

    user_log($_SESSION['ID'], 'banken', 'Setter inn: ' . number($number) . ' kr', $pdo);
    echo "Du satt inn " . number($number) . " kr i banken";
} else {
    user_log($_SESSION['ID'], 'banken', 'Ingen penger på hånden', $pdo);
    echo "Du har ingen penger på hånden";
}
