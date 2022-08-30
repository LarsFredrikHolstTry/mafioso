<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../auth.php';
include_once '../../functions/functions.php';

$money = remove_space($_POST['money']);

if (isset($money)) {
    user_log($_SESSION['ID'], 'banken', 'tar ut penger', $pdo);
    $number = $money;
    if (number_valid($number) && $number <= AS_session_row($_SESSION['ID'], 'AS_bankmoney', $pdo)) {
        give_money($_SESSION['ID'], $number, $pdo);
        take_bank_money($_SESSION['ID'], $number, $pdo);

        user_log($_SESSION['ID'], 'banken', 'tar ut ' . number($number) . ' kr', $pdo);
        echo "Du tok ut " . number($number) . " kr fra banken";
    } else {
        user_log($_SESSION['ID'], 'banken', 'Ugyldig sum: ' . $number, $pdo);
        echo "Ugyldig sum";
    }
}
