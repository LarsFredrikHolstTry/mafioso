<?php

ob_start();
include '../../../db_cred/db_cred.php';
include_once '../../functions/functions.php';
include_once '../../functions/dates.php';

$money_price = (price_buyout_jail($_SESSION['ID'], $pdo) - time()) * 50000;

echo $money_price;
