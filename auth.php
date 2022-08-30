<?php

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //ip from share internet
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //ip pass from proxy
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

if (isset($_SESSION['ID'])) {
    $time = time();

    $sql = "UPDATE accounts SET ACC_last_active = ?, ACC_ip_latest = ? WHERE ACC_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$time, $ip, $_SESSION['ID']]);
} else {
    header("Location: login.php");
    exit();
}
