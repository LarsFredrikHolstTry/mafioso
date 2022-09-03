<?php

ob_start();
include '../env.php';
include_once '../functions/functions.php';

$read = 1;
$id = $_POST['id']; 

$sql = "UPDATE notification SET NO_new = ? WHERE NO_acc_id = ? ";
$stmt= $pdo->prepare($sql);
$stmt->execute([$read, $_SESSION['ID']]);
