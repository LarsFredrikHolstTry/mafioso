<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';

if (AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo) > 0) {
    user_log($_SESSION['ID'], 'banken', 'tar ut alle penger', $pdo);
    $number = AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo);
    give_money($_SESSION['ID'], $number, $pdo);
    take_bank_money($_SESSION['ID'], $number, $pdo);

    user_log($_SESSION['ID'], 'banken', 'Tar ut ' . number($number) . ' kr', $pdo);
    echo "Du tok ut " . number($number) . " kr fra banken";
} else {
    user_log($_SESSION['ID'], 'banken', 'Ingen penger i banken', $pdo);
    echo "Du har ingen penger i banken";
}
