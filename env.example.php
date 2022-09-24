<?php

if (!session_id()) {
    session_start();
}

date_default_timezone_set('Europe/Oslo');

$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "mafiosov2";
$stripe_public = "pk_test_sGWuWqO4QUnwMHbgO9ERhc2i00Nnmj07eB";
$stripe_private = "sk_test_GHxvFFxnD2EtfTtQRqwyYkNy00Mi1oc331";
$stripe_db = "poeng_products_test";
$prodOrDev = "dev";

try {
    global $pdo;
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}

$days = [" ", "Mandag", "Tirsdag", "Onsdag", "Torsdag", "Fredag", "Lørdag", "Søndag"];
$month = [" ", "Januar", "Februar", "Mars", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Desember"];
